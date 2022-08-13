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
        $img = Image::make($image->getPath());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        });

        return self::save($path, new File($img));
    }
}
