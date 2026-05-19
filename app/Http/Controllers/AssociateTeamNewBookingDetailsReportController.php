<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\CustomerBooking;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class AssociateTeamNewBookingDetailsReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $associates = Associate::select('id', 'associate_id', 'associate_name')->get();
        $reports = collect();
        if ($request->has('search')) {
            $associateIds = [];
            if ($request->filled('associate_id')) {
                $associate = Associate::with('children')->find($request->associate_id);
                if ($associate) {
                    $associateIds[] = $associate->id;
                    $associateIds = array_merge($associateIds, $this->getAllChildrenIds($associate));
                }
            }
            $query = CustomerBooking::with([
                'associate.rank',
                'primaryDetail',
                'plotSaleDetail.plotDetail',
                'payment',
            ]);
            if (! empty($associateIds)) {
                $query->whereIn('associate_id', $associateIds);
            }
            if ($request->filled('from_date')) {
                $query->whereHas('plotSaleDetail',
                    function ($q) use ($request) {
                        $q->whereDate('booking_date', '>=', $request->from_date);
                    }
                );
            }
            if ($request->filled('to_date')) {
                $query->whereHas('plotSaleDetail',
                    function ($q) use ($request) {
                        $q->whereDate('booking_date', '<=', $request->to_date);
                    }
                );
            }
            $reports = $query->latest()->get();
        }

        return view('reports.associate-team-new-booking-details-report.index', compact('associates', 'reports'));
    }

    private function getAllChildrenIds($associate)
    {
        $ids = [];
        foreach ($associate->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildrenIds($child));
        }

        return $ids;
    }

    public function export(Request $request)
    {
        $associateIds = [];
        if ($request->filled('associate_id')) {
            $associate = Associate::with('children')->find($request->associate_id);
            if ($associate) {
                $associateIds[] = $associate->id;
                $associateIds = array_merge($associateIds, $this->getAllChildrenIds($associate));
            }
        }
        $query = CustomerBooking::with([
            'associate.rank',
            'primaryDetail',
            'plotSaleDetail.plotDetail',
            'payment',
        ]);
        if (! empty($associateIds)) {
            $query->whereIn('associate_id', $associateIds);
        }
        if ($request->filled('from_date')) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->whereDate('booking_date', '>=', $request->from_date);
                }
            );
        }
        if ($request->filled('to_date')) {
            $query->whereHas('plotSaleDetail',
                function ($q) use ($request) {
                    $q->whereDate('booking_date', '<=', $request->to_date);
                }
            );
        }
        $reports = $query->latest()->get();

        return $this->excelExportService->export($reports, 'associate-team-new-booking-details-report',
            [
                'Agent Id',
                'Position',
                'Customer Id',
                'Customer Name',
                'Booking Id',
                'Plot No',
                'Plan Type',
                'Payment Type',
                'Total Cost',
                'Paymode',
                'Paid Amt.',
                'Date',
            ],
            function ($report) {
                return [
                    $report->associate?->associate_id ?? 'N/A',
                    $report->associate?->rank?->designation ?? 'N/A',
                    $report->customer_code ?? 'N/A',
                    $report->primaryDetail?->name ?? 'N/A',
                    $report->booking_code ?? 'N/A',
                    $report->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    ucfirst($report->payment?->plan_type ?? 'N/A'),
                    ucfirst($report->payment?->payment_status ?? 'N/A'),
                    $report->plotSaleDetail?->total_plot_cost ?? 0,
                    ucfirst($report->payment?->payment_mode ?? 'N/A'),
                    $report->payment?->booking_amount ?? 0,
                    $report->created_at?->format('d-m-Y'),
                ];
            }
        );
    }
}
