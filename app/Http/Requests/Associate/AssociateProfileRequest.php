<?php

namespace App\Http\Requests\Associate;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssociateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ise TRUE karna mat bhoolna, warna 403 Forbidden error aayega
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Current logged-in user (associate) ki ID nikalne ke liye
        $associateId = $this->user() ? $this->user()->id : auth()->id();

        return [
            // Personal Details
            'associate_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'father_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',

            // Contact & Identifiers (Current ID ko ignore kiya hai unique check se)
            'mobile_number' => [
                'required',
                'string',
                'digits:10',
                Rule::unique('associates', 'mobile_number')->ignore($associateId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('associates', 'email')->ignore($associateId),
            ],
            'pancard_number' => [
                'required',
                'string',
                'size:10',
                Rule::unique('associates', 'pancard_number')->ignore($associateId),
            ],
            'aadhar_number' => [
                'required',
                'string',
                'digits:12',
                Rule::unique('associates', 'aadhar_number')->ignore($associateId),
            ],

            // Address Details
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',

            // Bank & Nominee Details
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:30',
            'ifsc_code' => 'required|string|max:15',
            'account_holder_name' => 'required|string|max:255',
            'nominee_name' => 'required|string|max:255',
            'nominee_relation' => 'required|string|max:100',
            'nominee_age' => 'required|integer|min:1|max:120',
        ];
    }
}
