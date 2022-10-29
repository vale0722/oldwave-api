<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|string|email|max:100',
            'city' => 'required|string',
            'address' => 'required|string',
            'document' => 'required|string',
            'document_type' => 'required|string',
            'car' => 'required',
            'car.*.slug' => 'required|string|exists:items,slug',
            'car.*.count' => 'required|int',
        ];
    }
}
