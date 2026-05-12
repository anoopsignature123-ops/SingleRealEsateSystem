@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Direct Associate
                </h3>

                <small class="text-muted">
                    Manage direct associates
                </small>

            </div>

        </div>


        {{-- Filter --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row">

                        <div class="col-md-3 mb-3">

                            <label class="mb-2">
                                Associate Id
                            </label>

                            <input type="text" name="associate_id" value="{{ request('associate_id') }}"
                                class="form-control" placeholder="Enter associate id">

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="mb-2">
                                From Date
                            </label>

                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="mb-2">
                                To Date
                            </label>

                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">

                        </div>


                        <div class="col-md-3 mb-3 d-flex align-items-end gap-2">

                            <button class="btn btn-success">
                                Search
                            </button>

                            <a href="{{ route('admin.direct-associate') }}" class="btn btn-danger">

                                Reset

                            </a>

                            <a href="{{ route('admin.direct-associate.export', request()->query()) }}" class="btn btn-info">

                                Export

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Table --}}
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-success">

                            <tr>

                                <th>SR No.</th>
                                <th>Associate Id</th>
                                <th>Associate Name</th>
                                <th>Sponsor Id</th>
                                <th>Sponsor Name</th>
                                <th>Mobile No</th>
                                <th>Registration Date</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($directAssociates as $key => $item)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>

                                        <span class="badge bg-danger px-3 py-2">

                                            {{ $item->associate_id }}

                                        </span>

                                    </td>

                                    <td>
                                        {{ $item->associate_name }}
                                    </td>

                                    <td>
                                        {{ $item->sponsor_id }}
                                    </td>

                                    <td>
                                        {{ $item->sponsor?->associate_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $item->mobile_number ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $item->created_at?->format('d-m-Y') }}
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

            if ($('#directAssociateTable tbody tr').length > 1 ||
                $('#directAssociateTable tbody td').text().trim() !== 'No data found') {

                $('#directAssociateTable').DataTable({

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
