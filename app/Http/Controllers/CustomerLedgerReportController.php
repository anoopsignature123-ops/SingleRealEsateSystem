<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\CustomerBooking;
use App\Models\Project;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class CustomerLedgerReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $projects = Project::select('id', 'name')->get();
        $ledger = $this->buildQuery($request)->first();

        return view('reports.customer-ledger-report.index', compact('projects', 'ledger'));
    }

    public function export(Request $request)
    {
        $ledger = $this->buildQuery($request)->first();

        if (!$ledger) {
            return back()->with('error', 'No ledger data found.');
        }

        return $this->excelExportService->export(
            $ledger->payments,
            'customer-ledger-report',
            [
                'Booking ID',
                'Customer ID',
                'Customer Name',
                'Project',
                'Block',
                'Plot No',
                'Receipt No',
                'Payment Type',
                'Paid Amount',
                'Payment Mode',
                'Status',
                'Date',
                'Remark',
            ],
            function ($payment) use ($ledger) {
                return [
                    $ledger->booking_code ?? 'N/A',
                    $ledger->customer_code ?? 'N/A',
                    $ledger->primaryDetail?->name ?? 'N/A',
                    $ledger->plotSaleDetail?->project?->name ?? 'N/A',
                    $ledger->plotSaleDetail?->block?->block ?? 'N/A',
                    $ledger->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A',
                    $payment->receipt_number ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $payment->transaction_category ?? $payment->payment_status ?? 'N/A')),
                    number_format($payment->paid_amount ?? $payment->booking_amount ?? 0, 2, '.', ''),
                    strtoupper($payment->payment_mode ?? 'N/A'),
                    strtoupper($payment->cheque_status ?? $payment->payment_status ?? 'CLEAR'),
                    $payment->created_at?->format('d-m-Y') ?? 'N/A',
                    $payment->remark ?? 'N/A',
                ];
            }
        );
    }

    private function buildQuery(Request $request)
    {
        $query = CustomerBooking::with([
            'primaryDetail.correspondenceDetail',
            'plotSaleDetail.project',
            'plotSaleDetail.block',
            'plotSaleDetail.plotDetail',
            'payment',
            'payments',
        ]);

        if ($request->filled('customer_id')) {
            $query->where('id', $request->customer_id);
        }

        if ($request->filled('booking_id')) {
            $query->where('booking_code', $request->booking_id);
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

        if ($request->filled('plot_id')) {
            $query->whereHas('plotSaleDetail.plotDetail', function ($q) use ($request) {
                $q->where('id', $request->plot_id);
            });
        }

        return $query;
    }

    public function getBlocks($projectId)
    {
        $blocks = Block::whereIn(
            'id',
            CustomerBooking::whereHas('plotSaleDetail', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })
                ->with('plotSaleDetail')
                ->get()
                ->pluck('plotSaleDetail.block_id')
                ->filter()
                ->unique()
        )
            ->select('id', 'block')
            ->get();

        return response()->json($blocks);
    }

    public function getCustomers($projectId, $blockId)
    {
        $customers = CustomerBooking::with('primaryDetail')
            ->whereHas('plotSaleDetail', function ($q) use ($projectId, $blockId) {
                $q->where('project_id', $projectId)
                    ->where('block_id', $blockId);
            })
            ->get();

        return response()->json($customers);
    }

    public function getPlots($customerId)
    {
        if (!$customerId || $customerId === 'null') {
            return response()->json([]);
        }

        $plots = CustomerBooking::with('plotSaleDetail.plotDetail')
            ->where('id', $customerId)
            ->get();

        return response()->json($plots);
    }

    public function getBooking($plotId, $customerId)
    {
        $booking = CustomerBooking::where('id', $customerId)
            ->whereHas('plotSaleDetail', function ($q) use ($plotId) {
                $q->where('plot_detail_id', $plotId);
            })
            ->first();

        return response()->json($booking);
    }
}