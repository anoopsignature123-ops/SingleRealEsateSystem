<?php

namespace App\Http\Requests;

use App\Models\CustomerBooking;
use Illuminate\Foundation\Http\FormRequest;

class CustomerBookingStepThreeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = request()->route('id');

        $customer = CustomerBooking::with([
            'primaryDocument',
            'secondaryDocument',
        ])->find($customerId);

        $primaryDoc = $customer?->primaryDocument;
        $secondaryDoc = $customer?->secondaryDocument;

        return [
            'dl_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($primaryDoc) {

                    if (
                        request()->has('dl') &&
                        ! $value &&
                        ! $primaryDoc?->dl_file
                    ) {
                        $fail('Driving license file is required.');
                    }
                },
            ],

            'aadhar_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($primaryDoc) {

                    if (
                        request()->has('aadhar') &&
                        ! $value &&
                        ! $primaryDoc?->aadhar_file
                    ) {
                        $fail('Aadhar file is required.');
                    }
                },
            ],

            'voter_id_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($primaryDoc) {

                    if (
                        request()->has('voter_id') &&
                        ! $value &&
                        ! $primaryDoc?->voter_id_file
                    ) {
                        $fail('Voter ID file is required.');
                    }
                },
            ],

            'other_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($primaryDoc) {

                    if (
                        request()->has('other') &&
                        ! $value &&
                        ! $primaryDoc?->other_file
                    ) {
                        $fail('Other document file is required.');
                    }
                },
            ],

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                function ($attribute, $value, $fail) use ($primaryDoc) {

                    if (
                        request()->has('profile_enabled') &&
                        ! $value &&
                        ! $primaryDoc?->profile_picture
                    ) {
                        $fail('Profile picture is required.');
                    }
                },
            ],
            'secondary_dl_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($secondaryDoc) {

                    if (
                        request()->has('secondary_dl') &&
                        ! $value &&
                        ! $secondaryDoc?->dl_file
                    ) {
                        $fail('Secondary driving license file is required.');
                    }
                },
            ],

            'secondary_aadhar_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($secondaryDoc) {

                    if (
                        request()->has('secondary_aadhar') &&
                        ! $value &&
                        ! $secondaryDoc?->aadhar_file
                    ) {
                        $fail('Secondary aadhar file is required.');
                    }
                },
            ],

            'secondary_voter_id_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($secondaryDoc) {

                    if (
                        request()->has('secondary_voter_id') &&
                        ! $value &&
                        ! $secondaryDoc?->voter_id_file
                    ) {
                        $fail('Secondary voter ID file is required.');
                    }
                },
            ],

            'secondary_other_file' => [
                'nullable',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
                function ($attribute, $value, $fail) use ($secondaryDoc) {

                    if (
                        request()->has('secondary_other') &&
                        ! $value &&
                        ! $secondaryDoc?->other_file
                    ) {
                        $fail('Secondary other document file is required.');
                    }
                },
            ],

            'secondary_profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                function ($attribute, $value, $fail) use ($secondaryDoc) {

                    if (
                        request()->has('secondary_profile_enabled') &&
                        ! $value &&
                        ! $secondaryDoc?->profile_picture
                    ) {
                        $fail('Secondary profile picture is required.');
                    }
                },
            ],

        ];
    }
}
