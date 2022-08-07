<?php

namespace App\Models\Concerns\Repositories;

use App\Actions\Images\ImageAction;

trait ImageRepository
{
    public static function actions(): ImageAction
    {
        return new ImageAction();
    }
}
