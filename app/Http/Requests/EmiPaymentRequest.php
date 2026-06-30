<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmiPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('booking_amount')) {
            $amount = preg_replace('/[^\d.]/', '', (string) $this->input('booking_amount'));

            $this->merge([
                'booking_amount' => $amount,
            ]);
        }

        if ($this->has('plot_sale_detail_ids')) {
            $this->merge([
                'plot_sale_detail_ids' => collect((array) $this->input('plot_sale_detail_ids'))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'customer_booking_id' => 'required|exists:customer_bookings,id',
            'plot_sale_detail_id' => 'nullable|required_without:plot_sale_detail_ids|exists:plot_sale_details,id',
            'plot_sale_detail_ids' => 'nullable|array',
            'plot_sale_detail_ids.*' => 'exists:plot_sale_details,id',
            'booking_amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:cash,cheque,dd,neft_rtgs,card',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'cheque_number' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'dd_number' => 'nullable|string|max:255',
            'transaction_number' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ];
    }
}
