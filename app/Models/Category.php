<?php

namespace App\Models;

use App\Models\Concerns\Filters\StatusFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use StatusFilter;

    protected $fillable = [
        'name',
        'code',
        'slug',
    ];
}
