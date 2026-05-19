@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h3 class="report-title">
                    <i class="fas fa-chart-line text-success me-2"></i>Associate Detail Report
                </h3>
                <small class="report-subtitle">Search and export associate reports</small>
            </div>
            <span class="badge bg-primary badge-report">Total: {{ count($agents) }}</span>
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
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="fw-semibold mb-1">Associate</label>
                            <select name="associate_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($associateList as $associate)
                                    <option value="{{ $associate->id }}"
                                        {{ request('associate_id') == $associate->id ? 'selected' : '' }}>
                                        {{ $associate->associate_id }}
                                        /
                                        {{ $associate->associate_name }}
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
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary  ">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                            <a href="{{ route('admin.agent-detail-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.agent-detail-report.export', request()->all()) }}"
                                class="btn btn-success  ">
                                <i class="fas fa-file-excel me-1"></i>Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table text-success me-2"></i>
                    Report Data
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="associateReportTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Sponsor ID</th>
                                <th>Agent ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agents as $key => $agent)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $agent->sponsor_id }}</td>
                                    <td>{{ $agent->associate_id }}</td>
                                    <td>{{ $agent->associate_name }}</td>
                                    <td>{{ $agent->mobile_number }}</td>
                                    <td>{{ $agent->created_at->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
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
                responsive: true
            });
        });
    </script>
@endpush
