<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CancelBooking;
use App\Models\CustomerBooking;
use App\Models\Project;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class CancelPlotBookingReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $cancelBookings = $this->buildQuery($request)->latest()->get();

        $summary = [
            'total_records' => $cancelBookings->count(),
            'total_deduction' => $cancelBookings->sum(fn($item) => (float) ($item->deduction_amount ?? 0)),
            'total_refund' => $cancelBookings->sum(fn($item) => (float) ($item->refund_amount ?? 0)),
            'total_projects' => $cancelBookings->pluck('plotSaleDetail.project_id')->filter()->unique()->count(),
        ];

        $customerIds = CustomerBooking::with('primaryDetail')->select('id', 'customer_code')->get();
        $projects = Project::select('id', 'name')->get();
        $blocks = Block::select('id', 'block')->get();

        return view(
            'reports.cancel-plot-booking-report.index',
            compact('cancelBookings', 'customerIds', 'projects', 'blocks', 'summary')
        );
    }

    public function export(Request $request)
    {
        $cancelBookings = $this->buildQuery($request)->latest()->get();

        return $this->excelExportService->export(
            $cancelBookings,
            'cancel-booking-report',
            [
                'Booking ID',
                'Customer ID',
                'Customer Name',
                'Project',
                'Block',
                'Plot',
                'Deduction',
                'Refund',
                'Pay Mode',
                'Cancel Date',
            ],
            function ($item) {
                return [
                    $item->customerBooking?->booking_code ?? 'N/A',
                    $item->customerBooking?->customer_code ?? 'N/A',
                    $item->customerBooking?->primaryDetail?->name ?? 'N/A',
                    $item->plotSaleDetail?->project?->name ?? 'N/A',
                    $item->plotSaleDetail?->block?->block ?? 'N/A',
                    $item->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    number_format($item->deduction_amount ?? 0, 2, '.', ''),
                    number_format($item->refund_amount ?? 0, 2, '.', ''),
                    strtoupper($item->pay_mode ?? 'N/A'),
                    $item->created_at?->format('d-m-Y') ?? 'N/A',
                ];
            }
        );
    }

    private function buildQuery(Request $request)
    {
        $query = CancelBooking::with([
            'customerBooking.primaryDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
        ]);

        if ($request->filled('customer_id')) {
            $query->where('customer_booking_id', $request->customer_id);
        }

        if ($request->filled('project_id')) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        if ($request->filled('block_id')) {
            $query->whereHas('plotSaleDetail', function ($q) use ($request) {
                $q->where('block_id', $request->block_id);
            });
        }

        return $query;
    }
}