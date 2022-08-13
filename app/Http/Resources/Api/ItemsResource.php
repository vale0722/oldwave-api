<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ItemsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'brand' => $this->brand,
            'city' => $this->city,
            'price' => $this->price,
            'image' => Storage::disk('s3')->path($this->tumpnail),
            'currency' => $this->currency,
            'discount' => $this->discount,
            'description' => $this->description,
            'stock' => $this->stock,
            'rating' => 5,
            'category' => [
                'slug' => $this->category->slug,
                'name' => $this->category->name,
            ],
            'seller' => [
                'logo' => $this->seller->logo,
                'name' => $this->seller->name,
            ],
            'createdAt' => $this->created_at->format('Y-m-d h:m:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d h:m:s'),
        ];
    }
}
