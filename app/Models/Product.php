<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'city',
        'price',
        'discount',
        'description',
        'category_id',
        'seller_id'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
