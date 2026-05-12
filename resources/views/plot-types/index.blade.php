@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Plot Type
                </h3>

                <small class="text-muted">
                    Manage all plot types
                </small>
            </div>
            <a href="{{ route('admin.plot-types.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Add Plot Type
            </a>
        </div>
        <!-- Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="plotTypeTable">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>
                                    Plot Type Name
                                </th>
                                <th>
                                    Date
                                </th>
                                <th width="150">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plotTypes as $key => $plotType)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        {{ $plotType->plot_type_name }}
                                    </td>
                                    <td>
                                        {{ $plotType->date ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.plot-types.edit', $plotType->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.plot-types.destroy', $plotType->id) }}"
                                            class="d-inline delete-form">
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
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No plot types found
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
                $('#plotTypeTable tbody tr').length > 0 &&
                $('#plotTypeTable tbody tr td').attr('colspan') == undefined
            ) {
                $('#plotTypeTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    searching: true,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    language: {
                        search: "",
                        searchPlaceholder: "Search plot type..."
                    }
                });

            }

            $('.delete-btn').click(function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This plot type will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete it!'

                }).then((result) => {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });
    </script>
@endpush
