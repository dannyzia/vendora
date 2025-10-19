<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'shop_banner' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'business_hours' => 'nullable|array',
        ];
    }
}
