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
                    Associate Chain Report
                </h3>
                <small class="report-subtitle">
                    Search and export associate chain reports
                </small>
            </div>

            <span class="badge bg-primary badge-report">
                Total: {{ count($chainAssociates) }}
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

                        <div class="col-md-4">

                            <label class="fw-semibold mb-1">
                                Associate Name
                            </label>

                            <select name="associate_id" id="associate_id" class="form-select">

                                <option value="">
                                    Select
                                </option>

                                @foreach ($associates as $associate)
                                    <option value="{{ $associate->associate_id }}"
                                        {{ request('associate_id') == $associate->associate_id ? 'selected' : '' }}>

                                        {{ $associate->associate_name }}/{{ $associate->associate_id }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-8 d-flex align-items-end gap-2">

                            <button class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.associate-chain-report.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Reset

                            </a>

                            <a href="{{ route('admin.associate-chain-report.export', request()->all()) }}"
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

                    <table id="chainTable" class="table table-hover align-middle nowrap w-100">

                        <thead>

                            <tr>

                                <th>Sr.No</th>
                                <th>Agent Id</th>
                                <th>Agent Name</th>
                                <th>Sponsor Id</th>
                                <th>Sponsor Name</th>
                                <th>Contact No</th>
                                <th>Pancard No</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($chainAssociates as $key => $associate)
                                <tr>

                                    <td>{{ $key + 1 }}</td>

                                    <td>{{ $associate->associate_id }}</td>

                                    <td>{{ $associate->associate_name }}</td>

                                    <td>{{ $associate->sponsor_id }}</td>

                                    <td>{{ $associate->sponsor?->associate_name }}</td>

                                    <td>{{ $associate->mobile_number }}</td>

                                    <td>{{ $associate->pancard_number }}</td>

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

            $('#associate_id').select2({
                width: '100%'
            });

            $('#chainTable').DataTable({
                pageLength: 10,
                scrollX: true
            });

        });
    </script>
@endpush
