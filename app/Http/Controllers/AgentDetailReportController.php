<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class AgentDetailReportController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    public function index(Request $request)
    {
        $query = Associate::query();

        if ($request->associate_id) {
            $query->where('id', $request->associate_id);
        }

        if ($request->name) {
            $query->where('associate_name', 'like', "%{$request->name}%");
        }

        if ($request->mobile) {
            $query->where('mobile_number', 'like', "%{$request->mobile}%");
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $agents = $query->latest()->get();

        $associateList = Associate::get();

        return view(
            'reports.agent_detail_report.index',
            compact('agents', 'associateList')
        );
    }

    public function export(Request $request)
    {
        $query = Associate::query();

        if ($request->associate_id) {
            $query->where('id', $request->associate_id);
        }

        if ($request->name) {
            $query->where('associate_name', 'like', '%'.$request->name.'%');
        }

        if ($request->mobile) {
            $query->where('mobile_number', 'like', '%'.$request->mobile.'%');
        }

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $agents = $query->get();

        return $this->excelExportService->export(
            $agents,
            'associate-report',
            [
                'Sponsor ID',
                'Agent ID',
                'Name',
                'Mobile',
                'Date',
            ],
            function ($agent) {
                return [
                    $agent->sponsor_id,
                    $agent->associate_id,
                    $agent->associate_name,
                    $agent->mobile_number,
                    $agent->created_at->format('d-m-Y'),
                ];
            }
        );
    }
}
