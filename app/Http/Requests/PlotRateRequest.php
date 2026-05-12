<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlotRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'project_id' => [
                'required',
                'exists:projects,id',
            ],

            'block_id' => [
                'required',
                'exists:blocks,id',
            ],

            'plot_rate' => [
                'required',
                'numeric',
                'min:0',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'project_id.required' => 'Please select project.',

            'project_id.exists' => 'Selected project is invalid.',

            'block_id.required' => 'Please select block.',

            'block_id.exists' => 'Selected block is invalid.',

            'plot_rate.required' => 'Please enter plot rate.',

            'plot_rate.numeric' => 'Plot rate must be numeric.',

            'plot_rate.min' => 'Plot rate must be greater than 0.',

        ];
    }
}
