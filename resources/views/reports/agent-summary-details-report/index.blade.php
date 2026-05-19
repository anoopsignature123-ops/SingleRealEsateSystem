@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>Agent Summary Details
                </h3>
                <small class="text-muted">
                    Agent business summary report
                </small>
            </div>
            <span class="badge badge-report">Agent Summary</span>
        </div>
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>Filter Report
                </h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Level</label>
                            <select name="level" class="form-select">
                                <option value=""> All Level</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}"
                                        {{ request('level') == $level->id ? 'selected' : '' }}>
                                        {{ $level->designation }}
                                        ({{ $level->commission }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.agent-summary-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.agent-summary-details-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @php
            $totalDirect = $reports->sum('direct_business');
            $totalTeam = $reports->sum('team_business');
            $grandTotal = $reports->sum('total');
        @endphp
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Agent Summary Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="agentSummaryTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Associate Name</th>
                                <th>Position</th>
                                <th>Direct Business</th>
                                <th>Team Business</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="fw-semibold">{{ $report['associate_name'] }}</td>
                                    <td>{{ $report['position'] }}</td>
                                    <td class="text-primary fw-bold">
                                        ₹{{ number_format($report['direct_business'], 2) }}
                                    </td>
                                    <td class="text-success fw-bold">
                                        ₹{{ number_format($report['team_business'], 2) }}
                                    </td>
                                    <td class="text-danger fw-bold">
                                        ₹{{ number_format($report['total'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No Data Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- <tfoot>
                            <tr class="table-dark">
                                <th colspan="3" class="text-end">Grand Total</th>
                                <th>₹{{ number_format($totalDirect, 2) }}</th>
                                <th>₹{{ number_format($totalTeam, 2) }}</th>
                                <th>₹{{ number_format($grandTotal, 2) }}</th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#agentSummaryTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
