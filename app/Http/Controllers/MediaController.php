<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Public image routes. They generate a processed size on first request;
 * afterwards ImageHelper links straight to the static file.
 */
class MediaController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function thumbnail(string $file): BinaryFileResponse
    {
        return $this->media->serve($file, 'thumbs');
    }

    public function preview(string $file): BinaryFileResponse
    {
        return $this->media->serve($file, 'preview');
    }

    public function related(string $file): BinaryFileResponse
    {
        return $this->media->serve($file, 'related');
    }

    public function source(string $file): BinaryFileResponse
    {
        return $this->media->source($file);
    }

    public function resize(string $file, string $size = 'sm'): BinaryFileResponse
    {
        return $this->media->serve($file, $size);
    }
}
