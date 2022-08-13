<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'tumpnail' => config('filesystems.disks.s3.statics') . '/' . $this->tumpnail,
            'currency' => $this->currency,
            'discount' => $this->discount,
            'count_views' => $this->rating_items()->count(),
            'description' => $this->description,
            'stock' => $this->stock,
            'category_name' => $this->category->name,
            'category_slug' => $this->category->slug,
            'category_id' => $this->category->id,
            'seller' => $this->seller->name,
            'seller_logo' => config('filesystems.disks.s3.statics') . '/sellers/' . $this->seller->logo,
            'rating' => 5,
            'createdAt' => $this->created_at->format('Y-m-d h:m:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d h:m:s'),
            'images' => $this->images->map(fn ($image) => [
                'id' => $image->getKey(),
                'url' =>  config('filesystems.disks.s3.statics') . '/' . $image->url,
            ]),
        ];
    }
}
