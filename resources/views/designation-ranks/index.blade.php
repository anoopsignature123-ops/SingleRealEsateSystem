@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Designation / Rank Master</h3>
                <small class="text-muted">Manage designations</small>
            </div>
            <a href="{{ route('admin.designations.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i>
                Add Designation
            </a>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="designationTable">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Designation Name</th>
                                <th>Rank Number</th>
                                <th>Commission</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($designationRanks as $key => $designation)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $designation->designation }}</td>
                                    <td>{{ $designation->rank_number }}</td>
                                    <td>
                                        {{-- ₹ --}}
                                        {{ number_format($designation->commission, 2) }} %
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.designations.edit', $designation->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.designations.destroy', $designation->id) }}"
                                            method="POST" class="d-inline">

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
                                        No designation found
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
            if ($('#designationTable tbody tr td').attr('colspan') == undefined) {
                $('#designationTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    ordering: true,
                    searching: true
                });
            }
            $('.delete-btn').click(function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This record will be deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
