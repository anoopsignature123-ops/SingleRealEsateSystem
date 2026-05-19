@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold report-title mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Registered Plot Details Report
                </h3>
                <small class="text-muted">
                    Search and export registered plot reports
                </small>
            </div>

            <span class="badge badge-report">
                Total: {{ count($registries) }}
            </span>
        </div>

        {{-- Filter --}}
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
                            <label class="fw-semibold mb-1">Customer ID</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($customerIds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->customer_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-semibold mb-1">Customer Name</label>
                            <input type="text" id="customer_name" class="form-control" readonly
                                placeholder="Enter Customer Name">
                        </div>

                        <div class="col-md-4">
                            <label class="fw-semibold mb-1">Project Name</label>
                            <select name="project_id" id="project_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-semibold mb-1">Block</label>
                            <select name="block_id" id="block_id" class="form-select">
                                <option value="">All</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.registered-plot-details-report.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset
                            </a>

                            <a href="{{ route('admin.registered-plot-details-report.export', request()->all()) }}"
                                class="btn btn-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Export
                            </a>

                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card report-card">

            <div class="report-header">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Registered Plot Records
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table id="registryTable" class="table table-hover align-middle nowrap w-100">

                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Booking Id</th>
                                <th>Customer Name</th>
                                <th>Project Name</th>
                                <th>Plot No</th>
                                <th>Gata No</th>
                                <th>Seller Name</th>
                                <th>Registry No</th>
                                <th>Registry Date</th>
                                <th>Total Cost</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $total = 0; @endphp

                            @foreach ($registries as $key => $item)
                                @php
                                    $cost = $item->plotDetail?->plotSaleDetail?->total_plot_cost ?? 0;
                                    $total += $cost;
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->customerBooking?->booking_code }}</td>
                                    <td>{{ $item->customerBooking?->primaryDetail?->name }}</td>
                                    <td>{{ $item->project?->name }}</td>
                                    <td>{{ $item->plotDetail?->plot_number }}</td>
                                    <td>{{ $item->gata_number }}</td>
                                    <td>{{ $item->seller_name }}</td>
                                    <td>{{ $item->register_no }}</td>
                                    <td>{{ date('d-M-Y', strtotime($item->register_date)) }}</td>
                                    <td>₹{{ number_format($cost, 2) }}</td>
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

            $('#registryTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: false,
                scrollX: true
            });

        });

        $('#customer_id').change(function() {

            let id = $(this).val();

            if (id) {

                $.get('/admin/get-customer-details/' + id, function(res) {

                    $('#customer_name').val(res.name);

                });

            }

        });


        $('#project_id').change(function() {

            let projectId = $(this).val();

            $.get(
                '/admin/registered-project-blocks/' + projectId,
                function(res) {

                    $('#block_id').html(
                        '<option value="">All</option>'
                    );

                    $.each(res, function(i, row) {

                        $('#block_id').append(
                            `<option value="${row.id}">
                                ${row.block}
                            </option>`
                        );

                    });

                }
            );

        });
    </script>
@endpush
