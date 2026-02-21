<?php

namespace App\Http\Requests;

use App\Enums\OfferState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:offers,slug'],
            'image' => ['required', 'image', 'max:2048'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', new Enum(OfferState::class)],
        ];
    }
}
