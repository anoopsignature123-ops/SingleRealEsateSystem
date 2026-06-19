@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-4">

        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center"
                            style="width:56px;height:56px;">
                            <i class="fas fa-chart-line text-success me-2"></i>
                        </div>

                        <div>
                            <h3 class="fw-bold mb-1 text-dark">Associate Detail Report</h3>
                            <p class="text-muted mb-0 small">
                                Search and export associate reports
                            </p>
                        </div>
                    </div>

                    <div class="badge bg-light text-dark border rounded-pill px-3 py-2">
                        Total: {{ count($agents) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-filter text-success me-2"></i>
                    Filter Report
                </h5>
            </div>

            <div class="card-body">
                <form method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="fw-semibold mb-1">Associate</label>
                            <select name="associate_id" class="form-select">
                                <option value="">All Associates</option>
                                @foreach ($associateList as $associate)
                                    <option value="{{ $associate->id }}"
                                        {{ request('associate_id') == $associate->id ? 'selected' : '' }}>
                                        {{ $associate->associate_id }} / {{ $associate->associate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">Name</label>
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                placeholder="Enter name">
                        </div>

                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">Mobile</label>
                            <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control"
                                placeholder="Enter mobile">
                        </div>

                        <div class="col-md-1">
                            <label class="fw-semibold mb-1">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <label class="fw-semibold mb-1">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('agent-detail-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('agent-detail-report.export', request()->all()) }}" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i>
                                Export
                            </a>
                        </div>

                        <div class="col-12 d-flex gap-2 flex-wrap">

                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="card report-card">
            <div class="report-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table text-success me-2"></i>
                    Associate Records
                </h5>

                <small>Showing associate records</small>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="associateReportTable" class="table table-hover align-middle w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Sponsor ID</th>
                                <th>Agent ID</th>
                                <th>Associate / Agent Name</th>
                                <th>Associate Mobile Number</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($agents as $key => $agent)
                                <tr>
                                    <td># {{ $key + 1 }}</td>

                                    <td>
                                        <span class="badge border border-secondary text-secondary">
                                            {{ $agent->sponsor_id ?? 'Self' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge border border-success text-success">
                                            {{ $agent->associate_id ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $agent->associate_name ?? 'N/A' }}
                                        </div>
                                        <small class="text-muted">Associate</small>
                                    </td>

                                    <td>+91 {{ $agent->mobile_number ?? 'N/A' }}</td>

                                    <td>
                                        {{ $agent->created_at ? $agent->created_at->format('d-M-Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No associate records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#associateReportTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                responsive: false,
                scrollX: true
            });
        });
    </script>
@endpush
