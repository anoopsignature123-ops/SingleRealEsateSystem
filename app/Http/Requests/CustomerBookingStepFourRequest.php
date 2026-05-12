<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBookingStepFourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // Required Relations
            'project_id' => 'required|exists:projects,id',

            'block_id' => 'required|exists:blocks,id',

            'plot_detail_id' => 'required|exists:plot_details,id',

            // Plot Fields
            'plot_number' => 'required|string|max:50',

            'plot_rate' => 'required|numeric|min:0',

            'plot_area' => 'required|numeric|min:0',

            'plot_cost' => 'required|numeric|min:0',

            'plc_amount' => 'nullable|numeric|min:0',

            // Charges
            'total_development_charge' => 'nullable|numeric|min:0',

            'development_rate' => 'nullable|numeric|min:0',

            'other_charges' => 'nullable|numeric|min:0',

            'coupon_discount' => 'nullable|numeric|min:0',

            'final_payable' => 'required|numeric|min:0',

            'total_plot_cost' => 'required|numeric|min:0',

            // Other
            'booking_date' => 'required|date',

            'remark' => 'nullable|string|max:500',

        ];
    }

    public function messages(): array
    {
        return [

            'project_id.required' => 'Please select property.',
            'project_id.exists' => 'Selected property is invalid.',

            'block_id.required' => 'Please select block.',
            'block_id.exists' => 'Selected block is invalid.',

            'plot_detail_id.required' => 'Please select plot.',
            'plot_detail_id.exists' => 'Selected plot is invalid.',

            'plot_number.required' => 'Plot number is required.',

            'plot_rate.required' => 'Plot rate is required.',

            'plot_area.required' => 'Plot area is required.',

            'plot_cost.required' => 'Plot cost is required.',

            'final_payable.required' => 'Final payable amount is required.',

            'total_plot_cost.required' => 'Total plot cost is required.',

            'booking_date.required' => 'Booking date is required.',

        ];
    }
}
