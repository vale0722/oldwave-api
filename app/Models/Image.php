<?php

namespace App\Models;

use App\Models\Concerns\Repositories\ImageRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    use ImageRepository;

    protected $fillable = [
        'url',
        'item_id',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
