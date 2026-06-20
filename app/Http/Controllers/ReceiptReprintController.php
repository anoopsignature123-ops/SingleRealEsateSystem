<?php

namespace App\Http\Controllers;

use App\Models\CustomerBooking;
use App\Models\PlotDetail;
use App\Services\ReceiptReprintService;
use Illuminate\Http\Request;

class ReceiptReprintController extends Controller
{
    public function __construct(
        protected ReceiptReprintService $service
    ) {}

    public function index()
    {
        $plots = PlotDetail::select('id', 'plot_number')
            ->orderBy('plot_number')
            ->get();

        return view('payment.receipt-reprint.index', compact('plots'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:plot_details,id',
            'customer_booking_id' => 'required|exists:customer_bookings,id',
        ], [
            'plot_id.required' => 'Please select plot number.',
            'customer_booking_id.required' => 'Please select customer.',
        ]);

        $plots = PlotDetail::select('id', 'plot_number')
            ->orderBy('plot_number')
            ->get();

        $receipts = $this->service->search(
            $request->plot_id,
            $request->customer_booking_id
        );

        $summary = [
            'count' => $receipts->count(),
            'amount' => (float) $receipts->sum(fn ($receipt) => $receipt->paid_amount ?? $receipt->booking_amount ?? 0),
            'latest' => $receipts->max('created_at'),
        ];

        return view('payment.receipt-reprint.index', compact('plots', 'receipts', 'summary'))
            ->with($request->only(['plot_id', 'customer_booking_id']));
    }

    public function download($paymentId)
    {
        return $this->service->downloadPdf($paymentId);
    }

    public function getCustomersByPlot($plotId)
    {
        $customers = CustomerBooking::with('primaryDetail')
            ->whereHas('payments', function ($query) use ($plotId) {
                $query->whereHas('plotSaleDetail', function ($q) use ($plotId) {
                    $q->where('plot_detail_id', $plotId);
                });
            })
            ->orderBy('customer_code')
            ->get()
            ->unique('id')
            ->values()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'text' => $booking->customer_code . ' / ' . ($booking->primaryDetail?->name ?? $booking->customer_name ?? 'N/A'),
                ];
            });

        return response()->json($customers);
    }
}
