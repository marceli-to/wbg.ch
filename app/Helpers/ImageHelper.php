<?php

namespace App\Helpers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ImageHelper
{
  static function get($image = NULL, $size = 'sm')
  {
   
    // Default src
    $src = '/media/' . $image . '/' . $size;

    // Overwrite with real image path
    if (File::exists(storage_path('app/public/media/images/processed/' . $size . '/') . $image))
    {
      $src = '/storage/media/images/processed/' . $size . '/' . $image;
    }

    return $src;
  }

  static function preview($image = NULL)
  {
  
    $src = '/media/preview/' . $image;

    // Overwrite with real image path
    if (File::exists(storage_path('app/public/media/images/processed/preview/') . $image))
    {
      $src = '/storage/media/images/processed/preview/' . $image;
    }

    return $src;
  }
}