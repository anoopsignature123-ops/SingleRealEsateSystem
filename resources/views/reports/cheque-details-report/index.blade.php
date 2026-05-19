@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-bank me-2"></i> Cheque Details Report
                </h3>
                <small class="text-muted">Cleared cheque payment details</small>
            </div>
            <span class="badge badge-report">Cheque Report</span>
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
                        <div class="col-md-3">
                            <label>Select Criteria</label>
                            <select name="criteria" class="form-select">
                                <option value="">
                                    All
                                </option>
                                <option value="full_payment" {{ request('criteria') == 'full_payment' ? 'selected' : '' }}>
                                    Full Payment
                                </option>
                                <option value="emi_plan" {{ request('criteria') == 'emi_plan' ? 'selected' : '' }}>
                                    Installment Amount
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Search</button>
                            <a href="{{ route('admin.cheque-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.cheque-details-report.export', request()->all()) }}"
                                class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i>Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card report-card">
            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>Cheque Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="chequeTable" class="table table-hover align-middle nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Payment Type</th>
                                <th>Pay Mode</th>
                                <th>Bank Account No.</th>
                                <th>Cheque No.</th>
                                <th>Bank Name</th>
                                <th>Bank Branch</th>
                                <th>Pay Date</th>
                                <th>Cheque Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $key => $report)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $report->customerBooking?->customer_code ?? 'N/A' }}</td>
                                    <td class="fw-semibold">{{ $report->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                    </td>
                                    <td class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $report->plan_type)) }}</td>
                                    <td>{{ strtoupper($report->payment_mode ?? 'N/A') }}</td>
                                    <td>{{ $report->account_number ?? 'N/A' }}</td>
                                    <td>{{ $report->cheque_number ?? 'N/A' }}</td>
                                    <td>{{ $report->bank_name ?? 'N/A' }}</td>
                                    <td>{{ $report->branch_name ?? 'N/A' }}</td>
                                    <td>{{ $report->cheque_date ? date('d-m-Y', strtotime($report->cheque_date)) : 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ strtoupper($report->cheque_status) }}
                                        </span>
                                    </td>
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
        $(function() {
            $('#chequeTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
        });
    </script>
@endpush
