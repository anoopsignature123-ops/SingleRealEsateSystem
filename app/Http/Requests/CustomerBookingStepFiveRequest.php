<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBookingStepFiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'plan_type' => 'required|in:full_payment,emi_plan',

            'payment_mode' => 'required|in:cash,cheque,dd,neft_rtgs,card',

            'booking_amount' => 'required|numeric|min:1',

            'due_amount' => 'required|numeric|min:0',

            // Full Payment
            'net_payable_amount' => 'nullable|required_if:plan_type,full_payment|numeric|min:0',

            // EMI
            'after_booking_payable_amount' => 'nullable|required_if:plan_type,emi_plan|numeric|min:0',

            // Bank Fields
            'account_number' => 'nullable|required_if:payment_mode,cheque,dd,neft_rtgs,card',

            'bank_name' => 'nullable|required_if:payment_mode,cheque,dd,neft_rtgs',

            'branch_name' => 'nullable|required_if:payment_mode,cheque,dd,neft_rtgs',

            'transaction_number' => 'nullable|required_if:payment_mode,cheque,dd,neft_rtgs',

            'cheque_date' => 'nullable|required_if:payment_mode,cheque,dd,neft_rtgs|date',

        ];
    }

    public function messages(): array
    {
        return [

            'plan_type.required' => 'Please select plan type.',

            'payment_mode.required' => 'Please select payment mode.',

            'booking_amount.required' => 'Booking amount is required.',

            'net_payable_amount.required_if' => 'Net payable amount is required.',

            'after_booking_payable_amount.required_if' => 'After booking payable amount is required.',

            'account_number.required_if' => 'Account number is required.',

            'bank_name.required_if' => 'Bank name is required.',

            'branch_name.required_if' => 'Branch name is required.',

            'transaction_number.required_if' => 'Transaction number is required.',

            'cheque_date.required_if' => 'Instrument date is required.',

        ];
    }
}
