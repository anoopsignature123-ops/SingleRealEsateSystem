<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlotDeatilRequest;
use App\Models\Block;
use App\Models\PlotDetail;
use App\Models\PlotType;
use App\Models\Project;
use App\Services\ExcelExportService;
use App\Services\PlotDetailService;
use Illuminate\Http\Request;

class PlotDetailController extends Controller
{
    public function __construct(
        private PlotDetailService $plotDetailService,
        private ExcelExportService $ExcelExportService
    ) {}

    public function getProjectData($id)
    {
        $project = Project::findOrFail($id);
        $blocks = Block::where('project_id', $id)->get();

        return response()->json(['location' => $project->location,'blocks' => $blocks,]);
    }

    public function index(Request $request)
    {
        $plotDetails = $this->plotDetailService->getAll($request);
        $projects = Project::orderBy('name')->get();

        return view('plot-details.index', compact('plotDetails', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        $blocks = Block::all();
        $plotTypes = PlotType::all();

        return view('plot-details.create', compact('projects', 'blocks', 'plotTypes'));
    }

    public function store(PlotDeatilRequest $request)
    {
        $this->plotDetailService->store($request->validated());

        return redirect()->route('admin.plot-details.index')->with('success', 'Plot created successfully');
    }

    public function show($id)
    {
        $plotDetail = $this->plotDetailService->show($id);
        $projects = Project::all();
        $blocks = Block::all();
        $plotTypes = PlotType::all();

        return view('plot-details.show', compact('plotDetail', 'projects', 'blocks', 'plotTypes'));
    }

    public function edit($id)
    {
        $plotDetail = $this->plotDetailService->find($id);
        $projects = Project::all();
        $blocks = Block::all();
        $plotTypes = PlotType::all();

        return view('plot-details.edit', compact('plotDetail', 'projects', 'blocks', 'plotTypes'));
    }

    public function update(PlotDeatilRequest $request, $id)
    {
        $this->plotDetailService->update($id, $request->validated());

        return redirect()->route('admin.plot-details.index')->with('success', 'Plot updated successfully');
    }

    public function destroy($id)
    {
        $this->plotDetailService->delete($id);

        return back()->with('success', 'Plot deleted successfully');
    }

    public function export(Request $request)
    {
        $plotDetails = $this->plotDetailService->getAll($request);

        return $this->ExcelExportService->export(
            data: $plotDetails,
            fileName: 'plot-details',
            headers: [
                'Project',
                'Location',
                'Block',
                'Plot Type',
                'Plot No',
                'Plot Area',
                'Plot Rate',
                'PLC Rate',
                'Status',
            ],

            callbackData: function ($plot) {
                return [
                    $plot->project?->name,
                    $plot->location,
                    $plot->block?->block,
                    $plot->plotType?->plot_type_name,
                    $plot->plot_number,
                    number_format($plot->plot_area, 2, '.', ''),
                    number_format($plot->plot_rate, 2, '.', ''),
                    number_format($plot->plc_rate, 2, '.', ''),
                    ucfirst($plot->status),
                ];
            }
        );
    }

    public function getProjectPlots($projectId)
    {
        return PlotDetail::where('project_id', $projectId)->select('plot_number')->distinct()->get();
    }
}
