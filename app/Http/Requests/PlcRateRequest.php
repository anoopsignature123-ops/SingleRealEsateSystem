<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlcRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plot_type_id' => 'required|exists:plot_types,id',
            'rate' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'plot_type_id.required' => 'Please select plot type.',
            'plot_type_id.exists' => 'Selected plot type is invalid.',

            'rate.required' => 'Please enter PLC rate.',
            'rate.numeric' => 'PLC rate must be numeric.',
            'rate.min' => 'PLC rate must be greater than 0.',
        ];
    }
}
