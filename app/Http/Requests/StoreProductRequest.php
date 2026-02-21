<?php

namespace App\Http\Requests;

use App\Enums\ProductState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'image' => ['required', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'state' => ['required', new Enum(ProductState::class)],
        ];
    }
}
