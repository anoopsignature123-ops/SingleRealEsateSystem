<?php

namespace App\Http\Controllers;

use App\Models\CancelBooking;
use App\Models\CustomerBooking;
use App\Models\PlotSaleDetail;
use App\Models\Project;
use Illuminate\Http\Request;

class CancelBookingController extends Controller
{
    public function index()
    {
        $projects = Project::select('id', 'name')->get();
        $plotSales = PlotSaleDetail::with([
            'project',
            'block',
            'plotDetail',
            'customerBooking.primaryDetail',
            'customerBooking.payments',
        ])
            ->whereHas('customerBooking', function ($query) {
                $query->where('status', '!=', 'cancelled');
            })
            ->get();

        return view('cancel-booking.index', compact('projects', 'plotSales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_booking_id' => 'required|exists:customer_bookings,id',
            'plot_sale_detail_id' => 'required|exists:plot_sale_details,id',
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

        CancelBooking::create($data);

        $booking = CustomerBooking::findOrFail($data['customer_booking_id']);
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('admin.cancel-booking.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
