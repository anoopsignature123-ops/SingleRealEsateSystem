<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Development amount is required.',
            'amount.numeric' => 'Development amount must be numeric.',
            'amount.min' => 'Development amount must be greater than 0.',
        ];
    }
}
