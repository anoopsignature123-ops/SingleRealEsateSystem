<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlotTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'plot_type_name' => 'required|string|max:255',

            'date' => 'nullable|date',

        ];
    }

    public function messages(): array
    {
        return [

            'plot_type_name.required' => 'Plot type name is required.',

        ];
    }
}
