@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Development Master
                </h3>

                <small class="text-muted">
                    Manage development amounts
                </small>
            </div>

            <a href="{{ route('admin.developments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Add Development
            </a>

        </div>


        <!-- Listing -->
        <div class="card shadow-sm border-0">
            @include('partials.master-header')
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="developmentTable">

                        <thead class="table-success">

                            <tr>

                                <th width="80">
                                    #
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th width="150">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($developments as $key => $development)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        ₹ {{ number_format($development->amount, 2, '.', '') }}
                                    </td>

                                    <td>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.developments.edit', $development->id) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <!-- Delete -->
                                        <form action="{{ route('admin.developments.destroy', $development->id) }}"
                                            method="POST" class="d-inline delete-form">

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

                                    <td colspan="3" class="text-center text-muted py-4">

                                        No development records found

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
            if ($('#developmentTable tbody tr td').attr('colspan') == undefined) {

                $('#developmentTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    ordering: true,
                    searching: true
                });

            }


            // Delete
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
