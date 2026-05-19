<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\AssociateAdvance;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class AssociateAdvanceReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $associates = Associate::select('id', 'associate_id', 'associate_name')->get();
        $reports = AssociateAdvance::with('associate');
        if ($request->filled('associate_id')) {
            $reports->where('associate_id', $request->associate_id);
        }
        $reports = $reports->latest()->get();

        return view('reports.associate-advance-report.index', compact('associates', 'reports')
        );
    }

    public function export(Request $request)
    {
        $reports = AssociateAdvance::with('associate');
        if ($request->filled('associate_id')) {
            $reports->where('associate_id', $request->associate_id);
        }
        $reports = $reports->latest()->get();

        return $this->excelExportService->export($reports, 'associate-advance-report',
            [
                'Associate Id',
                'Associate Name',
                'Advance Amount',
                'Advance Date',
                'Status',
                'Remark',
            ],
            function ($report) {
                return [
                    $report->associate?->associate_id ?? 'N/A',
                    $report->associate?->associate_name ?? 'N/A',
                    $report->advance_amount ?? 0,
                    $report->advance_date ? $report->advance_date->format('d-m-Y') : 'N/A', 'Approved',
                    $report->remarks ?? 'N/A',
                ];
            }
        );
    }
}
