@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Plot Details
                </h3>
                <small class="text-muted">
                    View Plot Details

                </small>
            </div>
            <a href="{{ route('admin.plot-details.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card shadow border-0">

            <div class="card-body">

                <div class="row">

                    {{-- Project --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->project->name ?? 'N/A' }}">
                    </div>
                    {{-- Location --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->location ?? 'N/A' }}">
                    </div>
                    {{-- Number Of Plots --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Number Of Plots</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->number_of_plots ?? 'N/A' }}">
                    </div>


                    {{-- Block --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Block</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->block->block ?? 'N/A' }}">
                    </div>


                    {{-- Plot Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot Type</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->plotType->plot_type_name ?? 'N/A' }}">
                    </div>


                    {{-- Plot Number --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot Number</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plot_number ?? 'N/A' }}">
                    </div>


                    {{-- Plot Range --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot No (From)</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->plot_no_from ?? 'N/A' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot No (To)</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plot_no_to ?? 'N/A' }}">
                    </div>


                    {{-- Plot Rate --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot Rate</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plot_rate ?? 'N/A' }}">
                    </div>


                    {{-- PLC Rate --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">PLC Rate</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plc_rate ?? 'N/A' }}">
                    </div>


                    {{-- Plot Area --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plot Area</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plot_area ?? 'N/A' }}">
                    </div>


                    {{-- Width --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Width (ft)</label>
                        <input type="text" readonly class="form-control" value="{{ $plotDetail->plot_width ?? 'N/A' }}">
                    </div>


                    {{-- Length --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Length (ft)</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $plotDetail->plot_length ?? 'N/A' }}">
                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" readonly class="form-control" value="{{ ucfirst($plotDetail->status) }}">
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
