@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush
@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0"><i class="bi bi-journal-text me-2"></i>Customer Ledger Report</h3>
                <small class="text-muted">Customer ledger & payment history</small>
            </div>
            <span class="badge badge-report">Ledger</span>
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
                            <label>Project Name</label>
                            <select name="project_id" id="project_id" class="form-select">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Block</label>
                            <select name="block_id" id="block_id" class="form-select">
                                <option value="">Select Block</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">Select Customer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Plot No</label>
                            <select name="plot_id" id="plot_id" class="form-select">
                                <option value="">Select Plot</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Booking ID</label>
                            <input type="text" id="booking_id" name="booking_id" class="form-control"
                                placeholder="Booking ID" readonly>
                        </div>
                        <div class="col-md-4 d-flex gap-2 align-items-end">
                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                            <a href="{{ route('admin.customer-ledger-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset
                            </a>
                            <a href="{{ route('admin.customer-ledger-report.export', request()->all()) }}"
                                class="btn btn-success"><i class="bi bi-file-earmark-excel me-1"></i> Export
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (isset($ledger))
            <div class="card report-card mb-4">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-vcard me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="text-muted">Customer Name</label>
                            <div class="fw-semibold">{{ $ledger->primaryDetail?->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Booking ID </label>
                            <div class="fw-semibold">{{ $ledger->booking_code ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Project</label>
                            <div class="fw-semibold">{{ $ledger->plotSaleDetail?->project?->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted">Plot No</label>
                            <div class="fw-semibold">
                                {{ $ledger->plotSaleDetail?->plotDetail?->plot_number ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Payment Table --}}
            <div class="card report-card">
                <div class="report-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2"></i>
                        Payment Transactions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ledgerTable" class="table table-hover align-middle nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receipt No</th>
                                    <th>Payment Type</th>
                                    <th>Paid Amount</th>
                                    <th>Payment Mode</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ledger->payments as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payment->receipt_number ?? 'N/A' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_status ?? 'N/A')) }}</td>
                                        <td class="text-success fw-bold">
                                            ₹{{ number_format($payment->booking_amount ?? 0, 2) }}</td>
                                        <td>{{ strtoupper($payment->payment_mode ?? 'N/A') }}</td>
                                        <td>{{ strtoupper($payment->cheque_status ?? 'CLEAR') }}</td>
                                        <td>{{ $payment->created_at?->format('d-m-Y') }}</td>
                                        <td>{{ $payment->remark ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('#ledgerTable').DataTable({
                pageLength: 10,
                scrollX: true
            });
            $('#project_id').change(function() {
                let projectId = $(this).val();
                $('#block_id').html('<option value="">Select Block</option>');
                $('#customer_id').html('<option value="">Select Customer</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');
                if (projectId != '') {
                    $.get('/admin/ledger-project-blocks/' + projectId,
                        function(response) {
                            $.each(response, function(index, item) {
                                $('#block_id').append(
                                    `<option value="${item.id}">${item.block}</option>`);
                            });
                        }
                    );
                }
            });
            $('#block_id').change(function() {
                let projectId = $('#project_id').val();
                let blockId = $(this).val();
                $('#customer_id').html('<option value="">Select Customer</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');
                if (blockId != '') {
                    $.get('/admin/ledger-block-customers/' + projectId + '/' + blockId,
                        function(response) {
                            $.each(response, function(index, item) {
                                $('#customer_id').append(`
                                <option value="${item.customer_id}">${item.primary_detail.name}</option>`);
                            });
                        }
                    );
                }
            });
            $('#customer_id').change(function() {
                let customerId = $(this).val();
                $('#plot_id').html('<option value="">Select Plot</option>');
                $('#booking_id').val('');
                if (customerId != '') {
                    $.get('/admin/ledger-customer-plots/' + customerId,
                        function(response) {
                            $.each(response, function(index, item) {
                                $('#plot_id').append(`
                                <option value="${item.plot_sale_detail.plot_detail_id}">
                                    ${item.plot_sale_detail.plot_detail.plot_number}
                                </option>`);
                            });
                        }
                    );
                }
            });
            $('#plot_id').change(function() {
                let plotId = $(this).val();
                let customerId = $('#customer_id').val();
                if (plotId && customerId) {
                    $.get('/admin/ledger-plot-booking/' + plotId + '/' + customerId,
                        function(response) {
                            $('#booking_id').val(response.booking_code ?? '');
                        }
                    );
                }
            });
        });
    </script>
@endpush
