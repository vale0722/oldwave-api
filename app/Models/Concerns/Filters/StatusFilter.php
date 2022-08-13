<?php

namespace App\Models\Concerns\Filters;

use Illuminate\Database\Eloquent\Builder;

trait StatusFilter
{
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->whereNotNull('enabled_at');
    }
}
