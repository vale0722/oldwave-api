<?php

namespace App\Models;

use App\Models\Concerns\Filters\ItemFilter;
use App\Models\Concerns\Filters\StatusFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;
    use ItemFilter;
    use StatusFilter;

    protected $fillable = [
        'name',
        'brand',
        'city',
        'price',
        'discount',
        'description',
        'category_id',
        'seller_id',
    ];

    public function rating_items(): HasMany
    {
        return $this->hasMany(RatingItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
