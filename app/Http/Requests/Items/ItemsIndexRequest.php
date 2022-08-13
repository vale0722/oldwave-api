<?php

namespace App\Http\Requests\Items;

use Illuminate\Foundation\Http\FormRequest;

class ItemsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'sometimes|string|nullable|max:100',
            'category' => 'sometimes|string|exists:categories,slug',
        ];
    }
}
