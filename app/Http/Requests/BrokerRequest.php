<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrokerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brokerId = $this->route('broker')?->id ?? $this->route('broker');

        return [
            'name' => 'required|string|max:255',
            'mobile_number' => ['required', 'min:8', 'max:15',
                Rule::unique('brokers', 'mobile_number')->ignore($brokerId),
            ],
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pancard_number' => ['required',
                Rule::unique('brokers', 'pancard_number')->ignore($brokerId),
            ],
            'aadhar_number' => ['required',
                Rule::unique('brokers', 'aadhar_number')->ignore($brokerId),
            ],
            'address' => 'required|string',

            'bank_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
        ];
    }
}