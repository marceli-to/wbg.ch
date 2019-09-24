<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class MediaService
{
    /**
     * Path for source files
     */
    protected $path_source;

    /**
     * Path for uploads
     */
    protected $path_uploads;

    /**
     * Path for small images
     */
    protected $path_xsmall;

    /**
     * Path for small images
     */
    protected $path_small;

    /**
     * Path for large images
     */
    protected $path_large;

    /**
     * Path for thumbnails
     */
    protected $path_thumbs;

    /**
     * Size for square thumbnails
     */
    protected $size_thumbs = 200;

    /**
     * Size for square small images
     */    
    protected $size_sm = 600;

    /**
     * Maximum width for extra small landscape images
     */    
    protected $max_width_xs = 160;    

    /**
     * Maximum height for extra small portrait images
     */    
    protected $max_height_xs = 120;

    /**
     * Maximum width for small landscape images
     */    
    protected $max_width_sm = 900;    

    /**
     * Maximum height for small portrait images
     */    
    protected $max_height_sm = 500;

    /**
     * Maximum width for large landscape images
     */    
    protected $max_width_lg = 1600;    

    /**
     * Maximum height for large portrait images
     */    
    protected $max_height_lg = 900;

    /**
     * Image prefix
     */
    protected $prefix = 'strut.ch';
    
    public function __construct()
    {
        $this->path_source    = storage_path('app/public/media/');
        $this->path_large     = storage_path('app/public/media/large/');
        $this->path_xsmall    = storage_path('app/public/media/xsmall/');
        $this->path_small     = storage_path('app/public/media/small/');
        $this->path_thumbs    = storage_path('app/public/media/thumbs/');
        $this->path_downloads = storage_path('app/public/media/downloads');
        $this->path_uploads   = storage_path('app/public/tmp/uploads');
        $this->_mkdir();
    }

    /**
     * Upload the specified resource.
     *
     * @return array
     */

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $name = $this->_sanitizeFilename(trim($file->getClientOriginalName()));
        $name = uniqid() . '_' . $this->prefix . '_' . $name;
        $file->move($this->path_source, $name);

        // Get file extension to store in media model
        $filetype = \File::extension($this->path_source . $name);

        // Create thumbnail for preview
        $this->thumbnail($name);

        return ['name' => $name, 'filetype' => $filetype];
    }

    /**
     * Upload the specified resource.
     *
     * @return array
     */

    public function uploadDocument(Request $request)
    {
        $file = $request->file('file');
        $name = $this->_sanitizeFilename(trim($file->getClientOriginalName()));
        $name = uniqid() . '_' . $name;
        $file->move($this->path_downloads, $name);

        // Get file extension to store in media model
        $filetype = \File::extension($this->path_downloads . $name);

        return ['name' => $name, 'filetype' => $filetype];
    }

    /**
     * Generate a thumbnail image.
     * 
     * @param  str $image
     * @return \Illuminate\Http\Response
     */

    public function thumbnail($image = NULL)
    {
        if (!File::exists($this->path_thumbs . $image))
        {
            $image = \Image::make($this->path_source . $image)->fit($this->size_thumbs);
            $image->save($this->path_thumbs . $image->basename);
            return \Response::make($image, 200, ['Content-Type' => 'image/jpeg']);
        }
        else
        {
            $filename = $image;
            $img = \Image::cache(function($image) use ($filename) {
                return $image->make($this->path_thumbs . $filename);
            }, 300, false);
            return \Response::make($img, 200, ['Content-Type' => 'image/jpeg']);
        }
    }

    /**
     * Resize an image.
     * 
     * @param  str $image
     * @param  str $size
     * @return \Illuminate\Http\Response
     */

    public function resize($image, $size = 'sm')
    {
        if ($image != NULL)
        {
            // Generate small images
            if ($size == 'xs')
            {
                if (!File::exists($this->path_xsmall . $image))
                {
                    // Create image instance
                    $image = \Image::make($this->path_source . $image);
                    
                    // Resize image
                    $image->resize(null, $this->max_height_xs, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $image->save($this->path_xsmall . $image->basename);
                    return \Response::make($image, 200, ['Content-Type' => 'image/jpeg']);
                }
                else
                {   
                    $filename = $image;
                    $img = \Image::cache(function($image) use ($filename) {
                        return $image->make($this->path_xsmall . $filename);
                    }, 300, false);
                    
                    return \Response::make($img, 200, ['Content-Type' => 'image/jpeg']);
                }
            }

            // Generate small images
            if ($size == 'sm')
            {
                if (!File::exists($this->path_small . $image))
                {
                    // Create image instance
                    $image = \Image::make($this->path_source . $image);

                    // Get width and height
                    $width  = $image->getWidth();
                    $height = $image->getHeight();
                    
                    // Resize landscape image
                    if ($width > $height)
                    {
                        $image->resize($this->max_width_sm, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    else
                    {
                        $image->resize(null, $this->max_height_sm, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }

                    $image->save($this->path_small . $image->basename);
                    return \Response::make($image, 200, ['Content-Type' => 'image/jpeg']);
                }
                else
                {   
                    $filename = $image;
                    $img = \Image::cache(function($image) use ($filename) {
                        return $image->make($this->path_small . $filename);
                    }, 300, false);
                    
                    return \Response::make($img, 200, ['Content-Type' => 'image/jpeg']);
                }
            }

            // Generate large images
            if ($size == 'lg')
            {
                if (!File::exists($this->path_large . $image))
                {
                    // Create image instance
                    $image = \Image::make($this->path_source . $image);

                    // Get width and height
                    $width  = $image->getWidth();
                    $height = $image->getHeight();
                    
                    // Resize landscape image
                    if ($width > $height)
                    {
                        $image->resize($this->max_width_lg, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    else
                    {
                        $image->resize(null, $this->max_height_lg, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }

                    $image->save($this->path_large . $image->basename);
                    return \Response::make($image, 200, ['Content-Type' => 'image/jpeg']);
                }
                else
                {   
                    $filename = $image;
                    $img = \Image::cache(function($image) use ($filename) {
                        return $image->make($this->path_large . $filename);
                    }, 300, false);
                    
                    return \Response::make($img, 200, ['Content-Type' => 'image/jpeg']);
                }
            }
        }
    }

    /**
     * Delete a file from the storage, including all subfolders
     * 
     * @param str $filename
     */

    public function delete($filename)
    {
        $directories = Storage::allDirectories('public');
        foreach($directories as $d)
        {
            Storage::delete($d . '/'. $filename);
        }
    }

    /**
     * Sanitize a string
     *
     * @param str $string
     * @param boolean  $force_lowercase - Force the string to lowercase?
     * @param boolean  $anal - If set to *true*, will remove all non-alphanumeric characters.
     */

    private function _sanitizeFilename($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", ",", "<", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
        return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
    }

    /**
     * Create directories
     * 
     */

    private function _mkdir()
    {
        if (!File::isDirectory($this->path_uploads))
        {
            File::makeDirectory($this->path_uploads, 0775, true, true);
        }

        if (!File::isDirectory($this->path_downloads))
        {
            File::makeDirectory($this->path_downloads, 0775, true, true);
        }
        
        if (!File::isDirectory($this->path_source))
        {
            File::makeDirectory($this->path_source, 0775, true, true);
        }

        if (!File::isDirectory($this->path_thumbs))
        {
            File::makeDirectory($this->path_thumbs, 0775, true, true);
        }
        
        if (!File::isDirectory($this->path_xsmall))
        {
            File::makeDirectory($this->path_xsmall, 0775, true, true);
        }

        if (!File::isDirectory($this->path_small))
        {
            File::makeDirectory($this->path_small, 0775, true, true);
        }

        if (!File::isDirectory($this->path_large))
        {
            File::makeDirectory($this->path_large, 0775, true, true);
        }
    }
}