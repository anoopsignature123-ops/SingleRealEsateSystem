<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlotDeatilRequest extends FormRequest
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
        return [
            'project_id' => 'required|exists:projects,id',
            'block_id' => 'required|exists:blocks,id',
            'plot_type_id' => 'required|exists:plot_types,id',
            'location' => 'required|string|max:255',
            'number_of_plots' => 'nullable',
            'plot_number' => 'required|max:100',
            'plot_no_from' => ['nullable', 'string', 'max:100'],
            'plot_no_to' => ['nullable', 'string', 'max:100'],
            'plot_rate' => 'required|numeric',
            'plc_rate' => 'nullable|numeric',
            'plot_area' => 'required|numeric',
            'plot_width' => ['nullable', 'numeric', 'min:0'],
            'plot_length' => ['nullable', 'numeric', 'min:0'],
            'status' => 'required|in:available,booked',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Please select project.',
            'project_id.exists' => 'Selected project is invalid.',
            'block_id.required' => 'Please select block.',
            'block_id.exists' => 'Selected block is invalid.',
            'plot_type_id.required' => 'Please select plot type.',
            'plot_type_id.exists' => 'Selected plot type is invalid.',
            'location.required' => 'Location is required.',
            'plot_number.required' => 'Plot number is required.',
            'plot_number.max' => 'Plot number cannot exceed 100 characters.',
            'plot_rate.required' => 'Plot rate is required.',
            'plot_rate.numeric' => 'Plot rate must be numeric.',
            'plc_rate.numeric' => 'PLC rate must be numeric.',
            'plot_area.required' => 'Please enter plot area.',
            'plot_area.numeric' => 'Plot area must be a numeric value.',
            'status.required' => 'Please select status.',
            'status.in' => 'Selected status is invalid.',
        ];
    }
}
