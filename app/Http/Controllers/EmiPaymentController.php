<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmiPaymentRequest;
use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\CustomerPayment;
use App\Models\PlotDetail;
use App\Models\Project;
use App\Services\EmiPaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class EmiPaymentController extends Controller
{
    protected EmiPaymentService $service;

    public function __construct(EmiPaymentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $projects = Project::latest()->get();

        return view('payment.emi-payment.index', compact('projects'));
    }

    public function getBlocks(int $projectId): JsonResponse
    {
        $blocks = Block::where('project_id', $projectId)->orderBy('block')->get(['id', 'block']);

        return response()->json(['status' => true, 'data' => $blocks]);
    }

    public function getPlots(int $blockId): JsonResponse
    {
        $plots = PlotDetail::where('block_id', $blockId)
            ->whereHas('plotSaleDetail.payments', function ($query) {
                $query->where('plan_type', 'emi_plan')
                    ->where('booking_status', 'booked')
                    ->where('payment_status', 'pending');
            })
            ->orderBy('plot_number')
            ->get(['id', 'plot_number']);

        if ($plots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No pending EMI booking found in this block.',
            ]);
        }

        return response()->json(['status' => true, 'data' => $plots]);
    }

    public function getBookingDetails(int $plotId): JsonResponse
    {
        $booking = CustomerBooking::with(['primaryDetail', 'plotSaleDetails'])
            ->whereHas('plotSaleDetails', function ($query) use ($plotId) {
                $query->where('plot_detail_id', $plotId);
            })
            ->whereHas('payments', function ($query) {
                $query->where('plan_type', 'emi_plan');
            })
            ->first();

        if (! $booking) {
            return response()->json(['status' => false, 'message' => 'EMI booking not found.']);
        }

        $saleDetail = $booking->plotSaleDetails()
            ->where('plot_detail_id', $plotId)
            ->first();

        if (! $saleDetail) {
            return response()->json(['status' => false, 'message' => 'Plot sale details not found.']);
        }

        $payments = CustomerPayment::where('customer_booking_id', $booking->id)
            ->where('plot_sale_detail_id', $saleDetail->id)
            ->where('plan_type', 'emi_plan')
            ->orderBy('id')
            ->get();

        $firstPayment = $payments->first();
        $latestPayment = $payments->sortByDesc('id')->first();

        $totalCost = round((float) ($saleDetail->total_plot_cost ?? 0), 2);
        $totalPaid = round((float) $payments->where('booking_status', 'booked')->sum('paid_amount'), 2);
        $holdAmount = round((float) $payments->where('booking_status', 'hold')->sum('paid_amount'), 2);
        $dueAmount = round((float) ($latestPayment->due_amount ?? max(0, $totalCost - $totalPaid)), 2);
        $bookingAmount = round((float) ($firstPayment->booking_amount ?? 0), 2);
        $emiMonths = (int) ($latestPayment->emi_months ?? 0);
        $monthlyEmi = round((float) ($latestPayment->after_booking_payable_amount ?? 0), 2);
        $emiStartDate = $firstPayment?->created_at
            ? Carbon::parse($firstPayment->created_at)->format('d-M-Y')
            : '-';
        $monthsPassed = $payments->where('transaction_category', 'emi_payment')->count();

        $history = $payments->map(function ($payment) {
            return [
                'receipt_no' => $payment->receipt_number,
                'date' => $payment->created_at ? $payment->created_at->format('d-M-Y') : '-',
                'amount' => number_format((float) $payment->paid_amount, 2),
                'mode' => strtoupper(str_replace('_', '/', $payment->payment_mode)),
                'status' => ucfirst($payment->booking_status ?? 'N/A') . ' / ' . ucfirst($payment->payment_status ?? 'N/A'),
            ];
        })->values();

        return response()->json([
            'status' => true,
            'booking_db_id' => $booking->id,
            'plot_sale_id' => $saleDetail->id,
            'booking_code' => $saleDetail->booking_code ?? 'N/A',
            'customer_code' => $booking->customer_code,
            'customer_name' => $booking->primaryDetail?->name,
            'total_cost' => number_format($totalCost, 2, '.', ''),
            'booking_amount' => number_format($bookingAmount, 2, '.', ''),
            'total_paid' => number_format($totalPaid, 2, '.', ''),
            'hold_amount' => number_format($holdAmount, 2, '.', ''),
            'due_amount' => number_format($dueAmount, 2, '.', ''),
            'emi_months' => $emiMonths,
            'months_passed' => $monthsPassed,
            'monthly_emi' => number_format($monthlyEmi, 2, '.', ''),
            'emi_start_date' => $emiStartDate,
            'payment_history' => $history,
        ]);
    }

    public function store(EmiPaymentRequest $request)
    {
        $data = $request->validated();

        $oldPayment = CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
            ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
            ->where('plan_type', 'emi_plan')
            ->latest()
            ->first();

        if (! $oldPayment) {
            return back()->withErrors(['booking_amount' => 'Booking record not found.'])->withInput();
        }

        $dueAmount = round((float) $oldPayment->due_amount, 2);
        $paidAmount = round((float) $data['booking_amount'], 2);
        $monthlyEmi = round((float) $oldPayment->after_booking_payable_amount, 2);

        if (($paidAmount - $dueAmount) > 0.01) {
            return back()->withErrors(['booking_amount' => 'EMI amount cannot be greater than due amount.'])->withInput();
        }

        if ($paidAmount > $dueAmount) {
            $data['booking_amount'] = $dueAmount;
            $paidAmount = $dueAmount;
        }

        if ($paidAmount < $monthlyEmi && $paidAmount < $dueAmount) {
            return back()
                ->withErrors(['booking_amount' => 'Minimum EMI amount is Rs. ' . number_format($monthlyEmi, 2)])
                ->withInput();
        }

        $this->service->store($data);

        return back()->with('success', 'EMI payment added successfully.');
    }
}
