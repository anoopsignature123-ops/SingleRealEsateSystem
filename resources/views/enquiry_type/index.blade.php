@extends('layouts.app')

@push('title')
    Lead Type Management
@endpush

@section('content')
    @php
        $totalTypes = $enquiryTypes->count();
        $latestType = $enquiryTypes->sortByDesc('created_at')->first();
    @endphp

    <div class="container-fluid mt-4 transaction-page lead-management-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-tags"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Lead Management</span>
                        <h3 class="fw-bold mb-1 text-dark">Lead Type</h3>
                        <p class="text-muted mb-0 small">Manage enquiry types used to classify leads.</p>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalTypes }} Types</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-tags-fill"></i></span>
                    <div>
                        <small>Total Lead Types</small>
                        <strong>{{ $totalTypes }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <small>Latest Type</small>
                        <strong>{{ $latestType?->name ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @can('enquiry-type-modify')
            <div class="transaction-card mb-4">
                <div class="transaction-history-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="transaction-section-title-icon">
                            <i class="bi bi-plus-circle"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-1" id="formTitle">Add Lead Type</h5>
                            <small class="text-muted">Create or update lead type master data.</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-card-body">
                    @include('enquiry_type.form')
                </div>
            </div>
        @endcan

        <div class="transaction-card transaction-history-card mb-4">
            <div class="transaction-history-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-section-title-icon">
                        <i class="bi bi-table"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-1">Lead Type Records</h5>
                        <small class="text-muted">View and maintain available enquiry classifications.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalTypes }} Records</span>
            </div>

            <div class="transaction-table-wrap">
                <table class="table transaction-table align-middle mb-0" id="enquiryTypeTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead Type Name</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquiryTypes as $key => $type)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="role-avatar">{{ strtoupper(substr($type->name, 0, 1)) }}</span>
                                        <div>
                                            <div class="fw-bold text-dark">{{ ucfirst($type->name) }}</div>
                                            <small class="text-muted">Lead type</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $type->created_at ? $type->created_at->format('d M, Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('enquiry-type-modify')
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success editBtn"
                                                data-id="{{ $type->id }}">
                                                <i class="bi bi-pencil-square me-1"></i>
                                                Edit
                                            </button>

                                            <form action="{{ route('enquiry-type.destroy', $type->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">No access</span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-tags fs-1 d-block mb-2 text-muted"></i>
                                    No lead types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const hasRecords = {{ $totalTypes > 0 ? 'true' : 'false' }};

            if (hasRecords) {
                $('#enquiryTypeTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    columnDefs: [{
                        orderable: false,
                        targets: [3]
                    }],
                    language: {
                        search: "",
                        searchPlaceholder: "Search lead types..."
                    }
                });
            }

            $('.editBtn').click(function() {
                const id = $(this).data('id');
                const editUrl = "{{ route('enquiry-type.edit', '__ID__') }}".replace('__ID__', id);
                const updateUrl = "{{ route('enquiry-type.update', '__ID__') }}".replace('__ID__', id);

                $('.form-control').removeClass('is-invalid');

                $.get(editUrl, function(data) {
                    $('#formTitle').text('Edit Lead Type');
                    $('#name').val(data.name).focus();
                    $('#enquiryTypeForm').attr('action', updateUrl);
                    $('#methodField').html('@method('PUT')');
                    $('#submitBtn').html('<i class="bi bi-save me-1"></i> Update Type');
                    $('#cancelBtn').removeClass('d-none');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                });
            });

            $('#cancelBtn').click(function() {
                resetForm();
            });

            function resetForm() {
                $('#formTitle').text('Add Lead Type');
                $('#enquiryTypeForm')[0].reset();
                $('#enquiryTypeForm').attr('action', "{{ route('enquiry-type.store') }}");
                $('#methodField').html('');
                $('#name').removeClass('is-invalid');
                $('#submitBtn').html('<i class="bi bi-save me-1"></i> Save Type');
                $('#cancelBtn').addClass('d-none');
            }

            $(document).on('click', '.delete-btn', function() {
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Lead Type?',
                    text: 'This lead type will be removed from the master list.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
