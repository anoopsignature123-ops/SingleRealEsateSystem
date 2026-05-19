<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CancelBooking;
use App\Models\CustomerBooking;
use App\Models\Project;
use Illuminate\Http\Request;

class CancelPlotBookingReportController extends Controller
{
    public function index(Request $request)
    {
        $query = CancelBooking::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ]);
        if ($request->customer_id) {
            $query->where('customer_booking_id', $request->customer_id);
        }
        if ($request->project_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('project_id', $request->project_id);
                }
            );
        }
        if ($request->block_id) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->where('block_id', $request->block_id);
                }
            );
        }
        $cancelBookings = $query->latest()->get();
        $customerIds = CustomerBooking::select('id', 'customer_code')->get();
        $projects = Project::select('id', 'name')->get();
        $blocks = Block::select('id', 'block')->get();

        return view('reports.cancel-plot-booking-report.index',
            compact('cancelBookings', 'customerIds', 'projects', 'blocks')
        );
    }

    public function export(Request $request)
    {
        $query = CancelBooking::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ]);

        if ($request->customer_id) {
            $query->where('customer_booking_id', $request->customer_id);
        }

        $cancelBookings = $query->get();

        return $this->excelExportService->export($cancelBookings, 'cancel-booking-report',
            ['Booking ID', 'Customer Name', 'Project', 'Block', 'Plot', 'Deduction', 'Refund', 'Pay Mode', 'Cancel Date'],
            function ($item) {
                return [
                    $item->customerBooking?->booking_code,
                    $item->customerBooking?->primaryDetail?->name,
                    $item->plotSaleDetail?->project?->name,
                    $item->plotSaleDetail?->block?->block,
                    $item->plotSaleDetail?->plotDetail?->plot_number,
                    $item->deduction_amount,
                    $item->refund_amount,
                    strtoupper($item->pay_mode),
                    $item->created_at?->format('d-m-Y'),
                ];
            }
        );
    }
}
