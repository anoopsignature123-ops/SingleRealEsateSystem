<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = request()->route('company');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website_link' => 'required|url|max:255',
            'contact_no' => 'required|digits_between:10,15',
            'address' => 'required|string|max:1000',
            'status' => 'nullable|in:0,1',
            'logo' => $companyId
                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
