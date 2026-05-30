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
            ->whereHas('plotSaleDetail.customerBooking.payments', function ($query) {
                $query->where('plan_type', 'emi_plan')
                    ->where('payment_status', 'booked');
            })
            ->orderBy('plot_number')
            ->get(['id', 'plot_number']);

        if ($plots->isEmpty()) {
            $holdPlots = PlotDetail::where('block_id', $blockId)
                ->whereHas('plotSaleDetail.customerBooking.payments', function ($query) {
                    $query->where('plan_type', 'emi_plan')
                        ->where('payment_status', 'hold');
                })
                ->exists();

            return response()->json([
                'status' => false,
                'message' => $holdPlots
                    ? 'All EMI bookings in this block are currently on Hold.'
                    : 'No EMI booking found in this block.',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $plots,
        ]);
    }

    public function getBookingDetails(int $plotId): JsonResponse
    {
        $booking = CustomerBooking::with(['primaryDetail', 'plotSaleDetail', 'payments'])
            ->whereHas('plotSaleDetail', function ($query) use ($plotId) {
                $query->where('plot_detail_id', $plotId);
            })
            ->whereHas('payments', function ($query) {
                $query->where('plan_type', 'emi_plan');
            })->first();

        if (! $booking) {
            return response()->json(['status' => false]);
        }

        $saleDetail = $booking->plotSaleDetail;
        $payments = $booking->payments->where('plan_type', 'emi_plan');
        $firstPayment = $payments->first();

        $totalCost = $saleDetail->total_plot_cost ?? 0;
        $totalPaid = $payments->sum('booking_amount');
        $dueAmount = $totalCost - $totalPaid;
        $emiMonths = $firstPayment?->emi_months ?? 1;

        $bookingAmount = $firstPayment?->booking_amount ?? 0;
        $emiAmount = $totalCost - $bookingAmount;
        $monthlyEmi = $emiMonths > 0
            ? round($emiAmount / $emiMonths, 2)
            : 0;

        $emiStartDate = '-';
        $monthsPassed = 0;

        if ($firstPayment?->created_at) {
            $emiStartDate = Carbon::parse($firstPayment->created_at)
                ->format('d-M-Y');
        }

        $monthsPassed = $payments
            ->where('transaction_category', 'emi_payment')
            ->count();

        $history = $payments->map(function ($payment) {
            return [
                'receipt_no' => $payment->receipt_number,
                'date' => $payment->created_at ? $payment->created_at->format('d-M-Y') : '-',
                'amount' => $payment->booking_amount,
                'mode' => strtoupper($payment->payment_mode),
            ];
        });

        return response()->json([
            'status' => true,
            'booking_db_id' => $booking->id,
            'plot_sale_id' => $saleDetail->id,
            'booking_code' => $booking->booking_code,
            'customer_code' => $booking->customer_code,
            'customer_name' => $booking->primaryDetail?->name,
            'total_cost' => $totalCost,
            'booking_amount' => $firstPayment?->booking_amount ?? 0,
            'total_paid' => $totalPaid,
            'due_amount' => number_format($dueAmount, 2),
            'emi_months' => $emiMonths,
            'emi_start_date' => $emiStartDate,
            'months_passed' => $monthsPassed,
            'monthly_emi' => $monthlyEmi,
            'payment_history' => $history,
        ]);
    }

    public function store(EmiPaymentRequest $request)
    {
        $data = $request->validated();

        $oldPayment = CustomerPayment::where('customer_booking_id', $data['customer_booking_id'])
            ->where('plot_sale_detail_id', $data['plot_sale_detail_id'])
            ->latest()
            ->first();
        if (! $oldPayment) {
            return back()->withErrors([
                'booking_amount' => 'Booking record not found.',
            ]);
        }
        $dueAmount = (float) $oldPayment->due_amount;
        $paidAmount = (float) $data['booking_amount'];
        if ($paidAmount > $dueAmount) {
            return back()->withErrors([
                'booking_amount' => 'EMI amount cannot be greater than due amount.',
            ])->withInput();
        }
        $this->service->store($request->validated());

        return back()->with('success', 'EMI Payment Added Successfully');
    }
}
