<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocumentRequest extends FormRequest
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
            'nid_number' => 'required|string|max:50',
            'nid_front_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'nid_back_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'trade_license_number' => 'required|string|max:100',
            'trade_license_image' => 'required|image|mimes:jpeg,jpg,png,pdf|max:5120',
            'trade_license_expiry' => 'required|date|after:today',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'bank_routing_number' => 'nullable|string|max:50',
        ];
    }
}
