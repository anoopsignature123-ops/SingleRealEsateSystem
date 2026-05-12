<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'block' => 'required|string|max:100',
        ];
    }
}
