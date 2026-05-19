@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Heading --}}
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">

            <div>

                <h3 class="report-title">
                    <i class="fas fa-sitemap text-success me-2"></i>
                    Associate Direct Report
                </h3>

                <small class="report-subtitle">
                    Search and export associate direct reports
                </small>

            </div>

            <span class="badge bg-primary badge-report">
                Total: {{ count($directAssociates) }}
            </span>

        </div>


        {{-- Filter Card --}}
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

                        <div class="col-md-3">

                            <label class="fw-semibold mb-1">
                                Sponsor ID
                            </label>

                            <select name="sponsor_id" id="sponsor_id" class="form-select">

                                <option value="">
                                    All
                                </option>

                                @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->associate_id }}"
                                        {{ request('sponsor_id') == $sponsor->associate_id ? 'selected' : '' }}>

                                        {{ $sponsor->associate_name }}/{{ $sponsor->associate_id }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-2">

                            <label class="fw-semibold mb-1">
                                From Date
                            </label>

                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">

                        </div>


                        <div class="col-md-2">

                            <label class="fw-semibold mb-1">
                                To Date
                            </label>

                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">

                        </div>


                        <div class="col-md-5 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.associate-direct-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset

                            </a>

                            <a href="{{ route('admin.associate-direct-report.export', request()->all()) }}"
                                class="btn btn-success">

                                <i class="fas fa-file-excel me-1"></i>
                                Export

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Table Card --}}
        <div class="card report-card">

            <div class="report-header">

                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table text-success me-2"></i>
                    Report Data
                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="directAssociateTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>

                                <th>Sr.No</th>
                                <th>Associate ID</th>
                                <th>Associate Name</th>
                                <th>Sponsor ID</th>
                                <th>Sponsor Name</th>
                                <th>Rank</th>
                                <th>Mobile</th>
                                <th>Joining Date</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($directAssociates as $key => $associate)
                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $associate->associate_id }}</td>

                                    <td>{{ $associate->associate_name }}</td>

                                    <td>{{ $associate->sponsor?->associate_id }}</td>

                                    <td>{{ $associate->sponsor?->associate_name }}</td>

                                    <td>{{ $associate->rank?->rank_number ?? 'N/A' }}</td>

                                    <td>{{ $associate->mobile_number }}</td>

                                    <td>{{ $associate->created_at?->format('d-m-Y') }}</td>

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

            $('#sponsor_id').select2({
                width: '100%'
            });

            $('#directAssociateTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

        });
    </script>
@endpush
