@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Associate Downline
                </h3>

                <small class="text-muted">
                    Manage Associate Downline
                </small>

            </div>

        </div>
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                {{-- Filters --}}
                <form method="GET">

                    <div class="row mb-4">

                        <div class="col-md-3">
                            <label class="mb-2">
                                Agent Id
                            </label>

                            <input type="text" name="associate_id" value="{{ request('associate_id') }}"
                                placeholder="Enter agent id" class="form-control">
                        </div>


                        <div class="col-md-3">
                            <label class="mb-2">
                                From Date
                            </label>

                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>


                        <div class="col-md-3">
                            <label class="mb-2">
                                To Date
                            </label>

                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>


                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button type="submit" class="btn btn-success">

                                Search

                            </button>


                            <a href="{{ route('admin.associate-downline') }}" class="btn btn-secondary">

                                Reset

                            </a>


                            <a href="{{ route('admin.associate-downline.export', request()->query()) }}"
                                class="btn btn-info">

                                Export

                            </a>

                        </div>

                    </div>

                </form>



                {{-- Table --}}
                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="directAssociateTable">

                        <thead>

                            <tr>

                                <th>SR No.</th>

                                <th>Associate Id</th>

                                <th>Associate Name</th>

                                <th>Sponsor Id</th>

                                <th>Sponsor Name</th>

                                <th>Mobile No</th>

                                <th>Joining Date</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($associates as $key => $item)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>


                                    <td>

                                        <span class="badge bg-info px-3 py-2">

                                            {{ $item->associate_id }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $item->associate_name }}

                                    </td>


                                    <td>

                                        {{ $item->sponsor_id ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->sponsor?->associate_name ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->mobile_number ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->created_at?->format('d M, Y') }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        No data found

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

            if (
                $('#downlineTable tbody tr').length > 0 &&
                $('#downlineTable tbody tr td').attr('colspan') == undefined
            ) {

                $('#downlineTable').DataTable({

                    pageLength: 10,

                    ordering: true,

                    searching: false,

                    responsive: true,

                    lengthMenu: [5, 10, 25, 50]

                });

            }

        });
    </script>
@endpush
