<?php

namespace App\Constants;

use MyCLabs\Enum\Enum;

class EnumBase extends Enum
{
    public static function values(): array
    {
        return array_values(static::toArray());
    }

    public static function randomKey(): string
    {
        return array_rand(static::toArray());
    }

    public static function randomValue(): string
    {
        return static::toArray()[static::randomKey()];
    }
}
