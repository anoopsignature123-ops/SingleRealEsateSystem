<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'required|string|max:255',

            'location' => 'required|string|max:255',

            'date' => 'required|date',

        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'Site name is required.',

            'location.required' => 'Site location is required.',

            'date.required' => 'Project date is required.',

        ];
    }
}
