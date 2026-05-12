<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBookingStepTwoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [

            // Primary
            'name' => 'required',
            'title' => 'required',
            'relation_name' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',

            'permanent_address' => 'required',
            'pin_code' => 'required|digits:6',
            'city' => 'required',
            'state' => 'required',

            // Primary correspondence
            'same_as_permanent_address' => 'required',

            'correspondence_address' => 'required',
            'telephone_no' => 'nullable',
            'email' => 'nullable|email',

            // Toggle
            'fill_secondary_detail' => 'required|in:yes,no',
        ];

        if (
            $this->fill_secondary_detail == 'yes'
        ) {

            $rules = array_merge(
                $rules,
                [

                    'secondary_name' => 'required',
                    'secondary_title' => 'required',
                    'secondary_relation_name' => 'required',
                    'secondary_dob' => 'required|date',
                    'secondary_gender' => 'required',

                    'secondary_permanent_address' => 'required',
                    'secondary_pin_code' => 'required|digits:6',
                    'secondary_city' => 'required',
                    'secondary_state' => 'required',

                    'secondary_same_as_permanent_address' => 'required',

                    'secondary_correspondence_address' => 'required',

                ]
            );
        }

        return $rules;
    }
}
