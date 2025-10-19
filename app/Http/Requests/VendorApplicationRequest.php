<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorApplicationRequest extends FormRequest
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
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . auth()->user()->vendor->id,
            'shop_description' => 'required|string|min:50|max:1000',
            'business_type' => 'required|in:individual,company,partnership',
            'business_name' => 'required_if:business_type,company,partnership|nullable|string|max:255',
            'business_registration_number' => 'required_if:business_type,company,partnership|nullable|string|max:100',
            'business_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
        ];
    }
}
