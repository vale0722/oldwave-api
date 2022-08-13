<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;

class FilesHelper
{
    public static function save(string $path, File $file): string
    {
        Storage::disk('s3')->put($path, $file);

        return $path . '/' . $file->getPathname();
    }

    public static function resizeImg(string $path, File $file): string
    {
        $image = $file;
        $img = Image::make($image);
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->encode($file->getMimeType(), 60);

        Storage::disk('s3')->put($path, $img->stream());

        return $path . '/' . $file->getPathname();
    }
}
