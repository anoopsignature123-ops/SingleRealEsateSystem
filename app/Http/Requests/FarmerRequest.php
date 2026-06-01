<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Update case mein current farmer ko ignore karne ke liye ID fetch kar rahe hain
        $farmerId = $this->route('farmer') ? $this->route('farmer')->id : null;

        return [
            // Farmer Information
            'broker_id'     => 'required|exists:brokers,id',
            'name'          => 'required|string|max:255',
            'mobile_number' => ['required', 'min:8', 'max:15', Rule::unique('farmers', 'mobile_number')->ignore($farmerId)],
            'caste'         => 'required|in:General,OBC,SC,ST',
            'city'          => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
            'pancard_number'=> ['nullable', 'regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/', Rule::unique('farmers', 'pancard_number')->ignore($farmerId)],
            'aadhar_number' => ['nullable', 'min:12', 'max:20', Rule::unique('farmers', 'aadhar_number')->ignore($farmerId)],
            'address'       => 'nullable|string|max:500',

            'bank_name'           => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:20',
            'ifsc_code'           => 'required|string|min:8|max:15',
        ];
    }

    public function messages(): array
    {
        return [
        
            'broker_id.required'     => 'Please select a broker for the farmer.',
            'mobile_number.unique'   => 'This mobile number is already registered.',
            'mobile_number.min'      => 'Mobile number must be at least 8 digits.',
            'mobile_number.max'      => 'Mobile number must be no more than 15 digits.',
            'pancard_number.unique'  => 'This PAN card is already associated with another farmer.',
            'pancard_number.regex'   => 'Invalid PAN format. It should be (e.g., ABCDE1234F).',
            'aadhar_number.unique'   => 'This Aadhaar number is already registered.',
            'aadhar_number.min'      => 'Aadhaar number must be at least 12 digits.',
            'aadhar_number.max'      => 'Aadhaar number must be no more than 20 digits.',
            
            'bank_name.required'           => 'Bank name is mandatory.',
            'account_holder_name.required' => 'Account holder name is required.',
            'account_number.required'      => 'Please enter the bank account number.',
            'ifsc_code.required'           => 'IFSC code is mandatory.',
            'ifsc_code.min'                => 'IFSC code must be at least 8 characters.',
            'ifsc_code.max'                => 'IFSC code must be no more than 15 characters.',
        ];
    }
}