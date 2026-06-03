<?php

namespace App\Http\Controllers;

use App\Services\PlotTransferService;
use Illuminate\Http\Request;

class PlotTransferController extends Controller
{
    public function __construct(protected PlotTransferService $plotTransferService) {}

    public function index(Request $request)
    {
        $data = $this->plotTransferService->index($request->all());

        return view('plot_transfer.index', $data);
    }

    public function getBlocks($projectId)
    {
        return response()->json(
            $this->plotTransferService->getBlocks($projectId)
        );
    }

    public function getPlots($blockId)
    {
        return response()->json(
            $this->plotTransferService->getPlots($blockId)
        );
    }

    public function getBookingData($plotId)
    {
        return response()->json(
            $this->plotTransferService->getBookingData($plotId)
        );
    }

    public function getTransferCustomers($bookingId)
    {
        return response()->json(
            $this->plotTransferService->getTransferCustomers($bookingId)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plot_sale_detail_id' => 'required|exists:plot_sale_details,id',
            'new_customer_booking_id' => 'required|exists:customer_bookings,id',
            'transfer_charge' => 'nullable|numeric|min:0',
            'transfer_date' => 'nullable|date',
            'transfer_reason' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $this->plotTransferService->store($data);

        return response()->json([
            'status' => true,
            'message' => 'Plot transferred successfully.',
        ]);
    }
}