<?php

namespace App\Http\Controllers;

use App\Services\CancelBookingService;
use Illuminate\Http\Request;

class CancelBookingController extends Controller
{
    public function __construct(
        protected CancelBookingService $cancelBookingService
    ) {}

    public function index()
    {
        $data = $this->cancelBookingService->index();

        return view('cancel-booking.index', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_booking_id' => 'required|exists:customer_bookings,id',
            'plot_sale_detail_id' => 'required|exists:plot_sale_details,id',
            'plot_sale_detail_ids' => 'nullable|array|min:1',
            'plot_sale_detail_ids.*' => 'integer|exists:plot_sale_details,id',

            'deduction_amount' => 'nullable|numeric|min:0',
            'deduction_percentage' => 'nullable|numeric|min:0|max:100',
            'refund_amount' => 'nullable|numeric|min:0',

            'pay_mode' => 'nullable|in:cash,cheque,dd,neft_rtgs,card',
            'pay_date' => 'nullable|date',

            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
        ]);

        try {
            $this->cancelBookingService->store($data);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['cancel_booking' => $e->getMessage()]);
        }

        return redirect()
            ->route('cancel-booking.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
