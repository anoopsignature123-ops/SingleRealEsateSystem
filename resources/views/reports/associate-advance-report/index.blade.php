@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-currency-rupee me-2"></i>Associate Advance Report
                </h3>
                <small class="text-muted">Associate advance report details</small>
            </div>
            <span class="badge badge-report">Advance Report</span>
        </div>
        <div class="card report-card mb-4">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Report
                </h5>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Associate Id</label>
                            <select name="associate_id" class="form-select">
                                <option value="">Select Associate</option>
                                @foreach ($associates as $associate)
                                    <option value="{{ $associate->id }}"
                                        {{ request('associate_id') == $associate->id ? 'selected' : '' }}>
                                        {{ $associate->associate_id }}
                                        -
                                        {{ $associate->associate_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.associate-advance-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.associate-advance-report.export', request()->query()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @php
            $totalAdvance = $reports->sum('advance_amount');
        @endphp
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>Associate Advance Report
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="associateAdvanceTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Associate Id</th>
                                <th>Associate Name</th>
                                <th>Advance Amount</th>
                                <th>Advance Date</th>
                                {{-- <th>Status</th> --}}
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report->associate?->associate_id ?? 'N/A' }}</td>
                                    <td>{{ $report->associate?->associate_name ?? 'N/A' }}</td>
                                    <td class="text-success fw-bold"> ₹{{ number_format($report->advance_amount ?? 0, 2) }}
                                    </td>
                                    <td>{{ $report->advance_date ? $report->advance_date->format('d-m-Y') : 'N/A' }}</td>
                                    {{-- <td>
                                        <span class="badge bg-success">Paid</span>
                                    </td> --}}
                                    <td>{{ $report->remarks ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot>
                            <tr class="table-dark">
                                <th colspan="3" class="text-end">Total</th>
                                <th>₹{{ number_format($totalAdvance, 2) }}</th>
                                <th colspan="3"></th>
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
            $('#associateAdvanceTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
