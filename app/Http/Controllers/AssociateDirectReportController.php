<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use Illuminate\Http\Request;

class AssociateDirectReportController extends Controller
{
    public function index(Request $request)
    {
        $rootAssociateIds = Associate::whereNull('under_place_id')->pluck('associate_id');
        $query = Associate::with(['sponsor', 'rank'])->whereIn('sponsor_id', $rootAssociateIds);
        if ($request->sponsor_id) {
            $query->where('sponsor_id', $request->sponsor_id);
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $directAssociates = $query->latest()->get();
        $sponsors = Associate::whereNull('under_place_id')->get();

        return view('reports.associate-direct-report.index', compact('directAssociates', 'sponsors'));
    }

    public function export(Request $request)
    {
        $rootAssociateIds = Associate::whereNull('under_place_id')->pluck('associate_id');
        $query = Associate::with(['sponsor', 'rank'])->whereIn('sponsor_id', $rootAssociateIds);
        if ($request->sponsor_id) {
            $query->where('sponsor_id', $request->sponsor_id);
        }
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        $directAssociates = $query->latest()->get();

        return $this->excelExportService->export($directAssociates, 'associate-direct-report',
            ['Associate ID', 'Associate Name', 'Sponsor ID', 'Sponsor Name', 'Rank', 'Mobile', 'Joining Date'],
            function ($associate) {
                return [
                    $associate->associate_id ?? 'N/A',
                    $associate->associate_name ?? 'N/A',
                    $associate->sponsor?->associate_id ?? 'N/A',
                    $associate->sponsor?->associate_name ?? 'N/A',
                    $associate->rank?->rank_number ?? 'N/A',
                    $associate->mobile_number ?? 'N/A',
                    $associate->created_at ? $associate->created_at->format('d-m-Y') : 'N/A',
                ];
            }

        );
    }
}
