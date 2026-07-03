@extends('layouts.app')

@push('title')
    Registered Plot Details Report
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="transaction-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-file-earmark-text text-success"></i>
                    </span>

                    <div>
                        <span class="text-success fw-bold text-uppercase small">
                            Registered Plot Details Report
                        </span>
                        <h3 class="fw-bold text-dark mb-1">
                            Registered Plot Details Report
                        </h3>
                        <p class="text-muted small mb-0">
                            Search and export registered plot reports.
                        </p>
                    </div>
                </div>

                <a href="{{ route('registered-plot-details-report.export', request()->all()) }}"
                    class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>
        </div>

        {{-- Summary --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Records</small>
                        <h4 class="fw-bold mb-0">{{ $summary['total_records'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-primary-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Projects</small>
                        <h4 class="fw-bold text-primary mb-0">{{ $summary['total_projects'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-warning-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Blocks</small>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['total_blocks'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border border-success-subtle shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <small class="text-muted fw-semibold">Total Cost</small>
                        <h4 class="fw-bold text-success mb-0">
                            ₹{{ number_format($summary['total_cost'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Filter Report</h5>
                        <small class="text-muted">
                            Filter registered plots by customer, project and block.
                        </small>
                    </div>
                </div>

                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All Customers</option>
                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->customer_code }} - {{ $item->primaryDetail?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" id="customer_name" class="form-control" readonly
                                placeholder="Customer name">
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Project Name</label>
                            <select name="project_id" id="project_id" class="form-select">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <label class="form-label fw-semibold">Block</label>
                            <select name="block_id" id="block_id" class="form-select"
                                data-selected="{{ $selectedBlockId }}">
                                <option value="">All Blocks</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-md-6 d-flex gap-2">
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('registered-plot-details-report.index') }}"
                                class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DataTable --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-table text-success me-2"></i>
                            Registered Plot Records
                        </h5>
                        <small class="text-muted">
                            Complete list of registered plots.
                        </small>
                    </div>

                    <span class="badge bg-success-subtle text-success border rounded-pill px-3 py-2">
                        {{ $registries->count() }} Records
                    </span>
                </div>

                <div class="table-responsive">
                    <table id="registryTable" class="table table-hover align-middle nowrap w-100">
                        <thead class="table-success">
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot No</th>
                                <th>Gata No</th>
                                <th>Seller Name</th>
                                <th>Registry No</th>
                                <th>Registry Date</th>
                                <th class="text-end">Total Cost</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($registries as $key => $item)
                                @php
                                    $cost = $item->plotDetail?->plotSaleDetail?->total_plot_cost ?? 0;
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <div class="fw-bold">
                                            {{ $item->customerBooking?->booking_code ?? 'N/A' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $item->customerBooking?->customer_code ?? 'N/A' }}
                                        </small>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $item->customerBooking?->primaryDetail?->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <td>{{ $item->project?->name ?? 'N/A' }}</td>

                                    <td>{{ $item->block?->block ?? 'N/A' }}</td>

                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">
                                            {{ $item->plotDetail?->plot_number ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>{{ $item->gata_number ?? 'N/A' }}</td>

                                    <td>{{ $item->seller_name ?? 'N/A' }}</td>

                                    <td>{{ $item->register_no ?? 'N/A' }}</td>

                                    <td>
                                        {{ $item->register_date ? date('d-M-Y', strtotime($item->register_date)) : 'N/A' }}
                                    </td>

                                    <td class="text-end fw-bold text-success">
                                        ₹{{ number_format($cost, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                        <span class="text-muted">No registered plot records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="10" class="text-end">Total</td>
                                <td class="text-end text-success">
                                    ₹{{ number_format($summary['total_cost'], 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#registryTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true,
                language: {
                    emptyTable: 'No registered plot records found.'
                }
            });

            $('#customer_id').change(function() {
                let id = $(this).val();

                if (id) {
                    $.get('/get-customer-details/' + id, function(res) {
                        $('#customer_name').val(res.name);
                    });
                } else {
                    $('#customer_name').val('');
                }
            });

            function loadBlocks(projectId, selectedBlockId = '') {
                $('#block_id').html('<option value="">All Blocks</option>');

                if (!projectId) {
                    return;
                }

                $.get('/registered-project-blocks/' + projectId, function(res) {
                    $.each(res, function(i, row) {
                        let selected = String(selectedBlockId) === String(row.id) ? 'selected' : '';

                        $('#block_id').append(
                            `<option value="${row.id}" ${selected}>${row.block}</option>`
                        );
                    });
                });
            }

            $('#project_id').change(function() {
                loadBlocks($(this).val());
            });

            let selectedProjectId = $('#project_id').val();
            let selectedBlockId = $('#block_id').data('selected');

            if (selectedProjectId) {
                loadBlocks(selectedProjectId, selectedBlockId);
            }
        });
    </script>
@endpush