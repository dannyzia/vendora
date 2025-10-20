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
            // Shop Information
            'shop_name' => 'required|string|max:255|unique:vendors,shop_name,' . auth()->user()->vendor->id,
            'shop_description' => 'required|string|min:50|max:1000',

            // Business Information
            'business_type' => 'required|in:individual,company,partnership',
            'business_name' => 'required_if:business_type,company,partnership|nullable|string|max:255',
            'business_registration_number' => 'required_if:business_type,company,partnership|nullable|string|max:100',

            // International Address Structure
            'country' => 'required|string|max:100',
            'business_address' => 'required|string|max:500',
            'state_province_region' => 'required|string|max:100',
            'district_county' => 'nullable|string|max:100',
            'city_municipality' => 'required|string|max:100',
            'area_neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',

            // Contact Information
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'shop_name' => 'shop name',
            'shop_description' => 'shop description',
            'business_type' => 'business type',
            'business_name' => 'business name',
            'business_registration_number' => 'business registration number',
            'country' => 'country',
            'business_address' => 'street address',
            'state_province_region' => 'state/province/region',
            'district_county' => 'district/county',
            'city_municipality' => 'city/municipality',
            'area_neighborhood' => 'area/neighborhood',
            'postal_code' => 'postal code',
            'contact_person' => 'contact person',
            'contact_phone' => 'contact phone',
            'contact_email' => 'contact email',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'shop_name.required' => 'Please provide a name for your shop.',
            'shop_name.unique' => 'This shop name is already taken. Please choose another.',
            'shop_description.required' => 'Please describe your business.',
            'shop_description.min' => 'Shop description must be at least 50 characters.',
            'business_name.required_if' => 'Business name is required for companies and partnerships.',
            'business_registration_number.required_if' => 'Registration number is required for companies and partnerships.',
            'state_province_region.required' => 'Please select your state/province/region.',
            'city_municipality.required' => 'Please provide your city or municipality.',
        ];
    }
}
