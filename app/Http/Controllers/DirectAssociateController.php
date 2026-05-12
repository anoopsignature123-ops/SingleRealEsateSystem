<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class DirectAssociateController extends Controller
{
    protected $excelExportService;

    public function __construct(ExcelExportService $excelExportService)
    {
        $this->excelExportService = $excelExportService;
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('associate_id')) {
            $query->where('sponsor_id', trim($request->associate_id));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        return $query;
    }

    public function index(Request $request)
    {

        $rootAssociateIds = Associate::whereNull('under_place_id')->pluck('associate_id');
        $query = Associate::with(['sponsor'])->whereIn('sponsor_id', $rootAssociateIds);
        $this->applyFilters($query, $request);
        $directAssociates = $query->latest()->get();

        return view('direct-associate.index', compact('directAssociates'));
    }

    public function associateDownline(Request $request)
    {
        $query = Associate::with(['sponsor'])->whereNotNull('under_place_id');
        $this->applyFilters($query, $request);
        $associates = $query->orderBy('id')->get();

        return view('associate-downline.index', compact('associates'));
    }

    public function export(Request $request)
    {
        $query = Associate::with(['sponsor']);
        $this->applyFilters($query, $request);
        $data = $query->latest()->get();
        $headers = [
            'SR No',
            'Associate Id',
            'Associate Name',
            'Sponsor Id',
            'Sponsor Name',
            'Mobile No',
            'Registration Date',
        ];
        $count = 1;

        return $this->excelExportService->export($data, 'direct-associate-list', $headers, function ($item) use (&$count) {
            return [
                $count++,
                $item->associate_id,
                $item->associate_name,
                $item->sponsor_id,
                $item->sponsor?->associate_name,
                $item->mobile_number,
                $item->created_at?->format('d-m-Y'),
            ];
        }
        );
    }

    public function exportDownline(Request $request)
    {
        $query = Associate::with(['sponsor']);
        $this->applyFilters($query, $request);
        $data = $query->orderBy('id')->get();
        $headers = [
            'SR No',
            'Associate Id',
            'Associate Name',
            'Sponsor Id',
            'Sponsor Name',
            'Mobile No',
            'Joining Date',
        ];

        $count = 1;

        return $this->excelExportService->export($data, 'associate-downline-list', $headers, function ($item) use (&$count) {
            return [
                $count++,
                $item->associate_id,
                $item->associate_name,
                $item->sponsor_id,
                $item->sponsor?->associate_name,
                $item->mobile_number,
                $item->created_at?->format('d-m-Y'),
            ];
        }
        );
    }
}
