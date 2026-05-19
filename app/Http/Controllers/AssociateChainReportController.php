<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use Illuminate\Http\Request;

class AssociateChainReportController extends Controller
{
    public function index(Request $request)
    {
        $associates = Associate::orderBy('associate_name')->get();
        $query = Associate::with(['sponsor']);
        if ($request->associate_id) {
            $query->where(function ($q) use ($request) {
                $q->where('associate_id', $request->associate_id)->orWhere('sponsor_id', $request->associate_id);
            });
        }
        $chainAssociates = $query->latest()->get();
        return view('reports.associate-chain-report.index', compact('associates', 'chainAssociates'));
    }

    public function export(Request $request)
    {
        $query = Associate::with(['sponsor']);
        if ($request->associate_id) {
            $query->where(function ($q) use ($request) {
                $q->where('associate_id', $request->associate_id)
                    ->orWhere('sponsor_id', $request->associate_id);
            });
        }
        $chainAssociates = $query->get();

        return $this->excelExportService->export($chainAssociates, 'associate-chain-report',
            ['Agent ID', 'Agent Name', 'Sponsor ID', 'Sponsor Name', 'Contact No', 'Pancard No'],
            function ($associate) {
                return [
                    $associate->associate_id ?? 'N/A',
                    $associate->associate_name ?? 'N/A',
                    $associate->sponsor_id ?? 'N/A',
                    $associate->sponsor?->associate_name ?? 'N/A',
                    $associate->mobile_number ?? 'N/A',
                    $associate->pancard_number ?? 'N/A',
                ];
            }
        );
    }
}
