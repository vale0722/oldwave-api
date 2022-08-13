<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class ItemsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'required|string|min:2|max:255|unique:items',
            'name' => 'required|string|min:3|max:100',
            'brand' => 'required|string|min:2|max:100',
            'city' => 'required|string|min:2|max:60',
            'price' => 'required|numeric',
            'currency' => 'required|string|min:1|max:3',
            'discount' => 'sometimes',
            'description' => 'required|string|min:5',
            'stock' => 'required|numeric',
            'category' => 'required|integer|exists:categories,id',
            'seller' => 'required|integer|exists:sellers,id',
            'images' => 'required',
            'images.*' => 'required|file',
        ];
    }
}
