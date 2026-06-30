<?php

namespace App\Http\Controllers;

use App\Services\PaymentTransferService;
use Illuminate\Http\Request;

class PaymentTransferController extends Controller
{
    public function __construct(
        protected PaymentTransferService $paymentTransferService
    ) {}

    public function index()
    {
        $data = $this->paymentTransferService->index();

        return view('payment_transfer.index', $data);
    }

    public function getBlocks($projectId)
    {
        return response()->json(
            $this->paymentTransferService->getBlocks($projectId)
        );
    }

    public function getPlots($blockId)
    {
        return response()->json(
            $this->paymentTransferService->getPlots($blockId)
        );
    }

    public function getPayments($plotId)
    {
        return response()->json(
            $this->paymentTransferService->getPayments($plotId)
        );
    }

    public function getCustomers()
    {
        return response()->json(
            $this->paymentTransferService->getCustomers()
        );
    }

    public function getCustomerPlots($customerBookingId)
    {
        return response()->json(
            $this->paymentTransferService->getCustomerPlots($customerBookingId)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_ids' => 'required|array|min:1',
            'payment_ids.*' => 'required|exists:customer_payments,id',

            'new_customer_booking_id' => 'required|exists:customer_bookings,id',
            'new_plot_sale_detail_id' => 'required|exists:plot_sale_details,id',

            'transfer_date' => 'nullable|date',
            'transfer_reason' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        try {
            $this->paymentTransferService->store($data);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage() ?: 'Payment transfer failed.',
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment transferred successfully.',
        ]);
    }
}
