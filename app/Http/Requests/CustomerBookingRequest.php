<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'customer_type' => [

                'required',

                'in:returning_customer,sale_customer,sale_to_associate',

            ],

            'associate_id' => [

                'required_if:customer_type,sale_to_associate',

                'nullable',

                'exists:associates,id',

            ],

            'existing_customer_id' => [

                'required_if:customer_type,returning_customer',

                'nullable',

                'exists:customer_bookings,id',

            ],

            'associate_code' => [

                'nullable',

                'string',

                'max:50',

            ],

            'associate_name' => [

                'nullable',

                'string',

                'max:100',

            ],

        ];
    }

    public function messages(): array
    {
        return [

            'customer_type.required' => 'Please select customer type.',

            'associate_id.required_if' => 'Please select associate.',

            'associate_id.exists' => 'Selected associate is invalid.',

            'existing_customer_id.required_if' => 'Please select customer.',

            'existing_customer_id.exists' => 'Selected customer is invalid.',

        ];
    }
}
