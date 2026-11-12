<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Stores uploaded images/documents and generates the processed image sizes.
 *
 * Processed images are written to storage/app/public/media/images/processed/{size}
 * and are then served statically (see ImageHelper). The PHP routes only kick
 * in for the very first request of a size.
 */
class MediaService
{
    /** JPEG quality used for processed images (Intervention v2 default). */
    private const QUALITY = 90;

    /** Bounding box (px) per responsive size; scaled along the longer side. */
    private const MAX = [
        'sm' => 500,
        'md' => 1200,
        'lg' => 2000,
        'source' => 3000,
    ];

    private const THUMB_SIZE = 200;

    private const XS_HEIGHT = 120;

    private string $root;

    public function __construct()
    {
        $this->root = storage_path('app/public/media');
    }

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    */

    /**
     * Store an uploaded image, downscale the source and create its thumbnail.
     *
     * @return array{name: string, filetype: string}
     */
    public function upload(UploadedFile $file): array
    {
        $name = $this->safeName($file);
        $file->move($this->dir('source'), $name);

        try {
            $this->resizeSource($name);
            $this->generate($name, 'thumbs');
        } catch (DecoderException $e) {
            // Not a decodable image, whatever its mime type claimed
            $this->delete($name);

            throw ValidationException::withMessages(['file' => 'Die Datei ist kein gültiges Bild.']);
        }

        return ['name' => $name, 'filetype' => pathinfo($name, PATHINFO_EXTENSION)];
    }

    /**
     * Store an uploaded document (pdf).
     *
     * @return array{name: string, filetype: string}
     */
    public function uploadDocument(UploadedFile $file): array
    {
        $name = $this->safeName($file);
        $file->move($this->dir('downloads'), $name);

        return ['name' => $name, 'filetype' => pathinfo($name, PATHINFO_EXTENSION)];
    }

    /*
    |--------------------------------------------------------------------------
    | Serving (fallback routes)
    |--------------------------------------------------------------------------
    */

    /**
     * Return a processed image, generating it first if needed.
     *
     * @param  string  $size  thumbs|xs|sm|md|lg|preview|related
     */
    public function serve(string $image, string $size): BinaryFileResponse
    {
        $image = $this->filename($image);
        $path = $this->dir($size).$image;

        if (! File::exists($path)) {
            if (! File::exists($this->dir('source').$image)) {
                throw new NotFoundHttpException;
            }
            $this->generate($image, $size);
        }

        return response()->file($path);
    }

    /**
     * Return the (already downscaled) source image.
     */
    public function source(string $image): BinaryFileResponse
    {
        $path = $this->dir('source').$this->filename($image);

        if (! File::exists($path)) {
            throw new NotFoundHttpException;
        }

        return response()->file($path);
    }

    /*
    |--------------------------------------------------------------------------
    | Processing
    |--------------------------------------------------------------------------
    */

    /**
     * Generate one processed size of a source image.
     */
    public function generate(string $image, string $size): void
    {
        $image = $this->filename($image);
        $img = Image::decodePath($this->dir('source').$image);

        match ($size) {
            'thumbs' => $img->cover(self::THUMB_SIZE, self::THUMB_SIZE),
            'xs' => $img->scale(height: self::XS_HEIGHT),
            'sm', 'md', 'lg' => $this->fitLongSide($img, self::MAX[$size]),
            'preview' => $this->preview($img),
            'related' => $this->related($img),
        };

        $img->save($this->dir($size).$image, quality: self::QUALITY);
    }

    /**
     * Crop a source image into a new file.
     *
     * @param  array{image: string, coords?: array{w: float, h: float, x: float, y: float}}  $data
     * @return array{name: string}
     */
    public function crop(array $data): array
    {
        $source = $this->filename($data['image']);
        $img = Image::decodePath($this->dir('source').$source);

        if (! empty($data['coords'])) {
            $c = $data['coords'];
            $img->crop((int) floor($c['w']), (int) floor($c['h']), (int) floor($c['x']), (int) floor($c['y']));
        }

        // Replace the unique id prefix of the original name with a new one
        $name = uniqid().'_'.$this->stripPrefix($source);
        $img->save($this->dir('source').$name, quality: 100);

        return ['name' => $name];
    }

    /**
     * Copy a source image under a new unique name.
     */
    public function clone(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        $image = $this->filename($image);
        $name = uniqid().'_'.$image;
        File::copy($this->dir('source').$image, $this->dir('source').$name);

        return $name;
    }

    /**
     * Delete a file and all of its processed versions.
     */
    public function delete(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $filename = $this->filename($filename);

        foreach (['source', 'downloads', 'thumbs', 'xs', 'sm', 'md', 'lg', 'preview', 'related'] as $dir) {
            File::delete($this->dir($dir).$filename);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Downscale the stored source to at most 3000px on the long side.
     */
    private function resizeSource(string $name): void
    {
        $img = Image::decodePath($this->dir('source').$name);
        $this->fitLongSide($img, self::MAX['source']);
        $img->save($this->dir('source').$name, quality: 100);
    }

    /**
     * Scale landscape images by width and portrait images by height, once
     * that side reaches $max (mirrors the original v2 behaviour).
     */
    private function fitLongSide(ImageInterface $img, int $max): void
    {
        $width = $img->width();
        $height = $img->height();

        if ($width > $height && $width >= $max) {
            $img->scale(width: $max);
        } elseif ($height >= $max) {
            $img->scale(height: $max);
        }
    }

    /** Project list preview: 694px high, centre-cropped to 456×614. */
    private function preview(ImageInterface $img): void
    {
        if ($img->height() >= 694) {
            $img->scale(height: 694);
            $img->crop(456, 614, alignment: Alignment::CENTER);
        }
    }

    /** Related project teaser: 430px wide, centre-cropped to 430×278. */
    private function related(ImageInterface $img): void
    {
        if ($img->width() > 430 && $img->height() > 280) {
            $img->scale(width: 430);
            $img->crop(430, 278, alignment: Alignment::CENTER);
        }
    }

    /**
     * Unique, sanitized filename with an extension derived from the detected
     * mime type (never from the client supplied name).
     */
    private function safeName(UploadedFile $file): string
    {
        $base = pathinfo(trim($file->getClientOriginalName()), PATHINFO_FILENAME);
        $base = Str::slug(str_replace('.', '-', $base)) ?: 'file';
        $ext = $file->guessExtension();
        $ext = $ext === 'jpeg' ? 'jpg' : $ext;

        return uniqid().'_'.$base.'.'.$ext;
    }

    /**
     * Remove the "{uniqid}_" prefix from a stored filename.
     */
    private function stripPrefix(string $name): string
    {
        return preg_replace('/^[0-9a-f]{13}_/', '', $name);
    }

    /**
     * Reject anything that is not a plain filename (no paths).
     */
    private function filename(string $name): string
    {
        if ($name !== basename($name) || ! preg_match('/^[A-Za-z0-9._-]+$/', $name) || str_starts_with($name, '.')) {
            throw new NotFoundHttpException;
        }

        return $name;
    }

    /**
     * Absolute directory for a storage bucket, created on demand.
     */
    private function dir(string $bucket): string
    {
        $dir = match ($bucket) {
            'source' => $this->root.'/images/source/',
            'downloads' => $this->root.'/downloads/',
            'thumbs', 'xs', 'sm', 'md', 'lg', 'preview', 'related' => $this->root.'/images/processed/'.$bucket.'/',
        };

        File::ensureDirectoryExists($dir, 0775);

        return $dir;
    }
}
