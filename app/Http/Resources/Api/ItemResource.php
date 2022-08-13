<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'brand' => $this->brand,
            'city' => $this->city,
            'price' => $this->price,
            'currency' => $this->currency,
            'discount' => $this->discount,
            'description' => $this->description,
            'stock' => $this->stock,
            'category_name' => $this->category->name,
            'category_slug' => $this->category->slug,
            'seller' => $this->seller->name,
            'seller_logo' => Storage::disk('s3')->path('sellers/' . $this->seller->logo),
            'rating' => 5,
            'createdAt' => $this->created_at->format('Y-m-d h:m:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d h:m:s'),
            'images' => $this->images->map(fn ($image) => [
                'url' => Storage::disk('s3')->path($image->url),
            ]),
        ];
    }
}
