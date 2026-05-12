@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Plot Rate Master
                </h3>

                <small class="text-muted">
                    Manage plot rates
                </small>
            </div>

            <a href="{{ route('admin.plot-rates.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Add Plot Rate
            </a>

        </div>


        <!-- Listing -->
        <div class="card shadow-sm border-0">
            @include('partials.master-header')
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="plotRateTable">

                        <thead class="table-success">

                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Block</th>
                                <th>Plot Rate</th>
                                <th width="150">Action</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($plotRates as $key => $plotRate)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        {{ $plotRate->project?->name }}
                                    </td>

                                    <td>
                                        {{ $plotRate->block?->block }}
                                    </td>

                                    <td>
                                        {{ number_format($plotRate->plot_rate, 2, '.', '') }}
                                    </td>

                                    <td>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.plot-rates.edit', $plotRate->id) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <!-- Delete -->
                                        <form action="{{ route('admin.plot-rates.destroy', $plotRate->id) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center text-muted py-4">

                                        No plot rates found

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

            // DataTable
            if ($('#plotRateTable tbody tr td').attr('colspan') == undefined) {

                $('#plotRateTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    ordering: true,
                    searching: true
                });

            }


            // Delete Confirm
            $('.delete-btn').click(function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This record will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

            });

        });
    </script>
@endpush
