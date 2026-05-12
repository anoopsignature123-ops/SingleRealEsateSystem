@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    Roles Management
                </h2>
                <p class="text-muted mb-0">
                    Manage system roles and permissions
                </p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success px-4">
                <i class="bi bi-plus-circle me-1"></i>
                Add Role
            </a>
        </div>
        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border p-1 align-middle" id="rolesTable">
                        <thead class="table-success border-bottom">
                            <tr>
                                <th>#</th>
                                <th>Role Name</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $key => $role)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        {{ ucfirst($role->name) }}
                                    </td>
                                    <td class="text-center">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="btn btn-sm btn-light border me-1">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-light border delete-btn">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        No users found
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

            if ($('#rolesTable tbody tr').length > 0 &&
                $('#rolesTable tbody tr td').attr('colspan') == undefined) {
                $('#rolesTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    searching: true,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    language: {
                        search: "",
                        searchPlaceholder: "Search roles..."
                    }
                });
            }
            $('.delete-btn').click(function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete Role?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
