@extends('layouts.app')

@push('title')
    Lead Source Management
@endpush

@section('content')
    @php
        $totalSources = $sources->count();
        $latestSource = $sources->first();
    @endphp

    <div class="container-fluid mt-4 transaction-page lead-management-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-broadcast-pin"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Lead Management</span>
                        <h3 class="fw-bold mb-1 text-dark">Lead Source</h3>
                        <p class="text-muted mb-0 small">Manage where leads are coming from.</p>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalSources }} Sources</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-diagram-3"></i></span>
                    <div>
                        <small>Total Sources</small>
                        <strong>{{ $totalSources }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <small>Latest Source</small>
                        <strong>{{ $latestSource?->name ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @can('lead-source-modify')
            <div class="transaction-card mb-4">
                <div class="transaction-history-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="transaction-section-title-icon">
                            <i class="bi bi-plus-circle"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-1" id="formTitle">Add Lead Source</h5>
                            <small class="text-muted">Create or update source names used in lead entries.</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-card-body">
                    <form method="POST" id="sourceForm" action="{{ route('source.store') }}">
                        @csrf
                        <div id="methodField"></div>

                        <div class="row g-3 align-items-end">
                            <div class="col-lg-8">
                                <label class="form-label fw-semibold">Source Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter source name" required autocomplete="off" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4">
                                <div class="d-flex justify-content-lg-end gap-2">
                                    <button type="button" id="cancelBtn" class="btn btn-outline-secondary px-4 d-none">
                                        Cancel
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-success px-4">
                                        <i class="bi bi-save me-1"></i>
                                        Save Source
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        <h5 class="fw-bold mb-1">Source Records</h5>
                        <small class="text-muted">View and maintain lead source master data.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalSources }} Records</span>
            </div>

            <div class="transaction-table-wrap">
                <table class="table transaction-table align-middle mb-0" id="sourceTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Source Name</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sources as $key => $source)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="role-avatar">{{ strtoupper(substr($source->name, 0, 1)) }}</span>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $source->name }}</div>
                                            <small class="text-muted">Lead source</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $source->created_at ? $source->created_at->format('d M, Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('lead-source-modify')
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success editBtn"
                                                data-id="{{ $source->id }}" data-name="{{ $source->name }}">
                                                <i class="bi bi-pencil-square me-1"></i>
                                                Edit
                                            </button>

                                            <form action="{{ route('source.destroy', $source->id) }}" method="POST"
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
                                    <i class="bi bi-broadcast fs-1 d-block mb-2 text-muted"></i>
                                    No sources found.
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
            const hasRecords = {{ $totalSources > 0 ? 'true' : 'false' }};

            if (hasRecords) {
                $('#sourceTable').DataTable({
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
                        searchPlaceholder: "Search sources..."
                    }
                });
            }

            $('.editBtn').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const updateUrl = "{{ route('source.update', '__ID__') }}".replace('__ID__', id);

                $('#formTitle').text('Edit Lead Source');
                $('#name').val(name).focus().removeClass('is-invalid');
                $('#sourceForm').attr('action', updateUrl);
                $('#methodField').html('@method('PUT')');
                $('#submitBtn').html('<i class="bi bi-save me-1"></i> Update Source');
                $('#cancelBtn').removeClass('d-none');
                $('html, body').animate({ scrollTop: 0 }, 'fast');
            });

            $('#cancelBtn').click(function() {
                resetForm();
            });

            function resetForm() {
                $('#formTitle').text('Add Lead Source');
                $('#sourceForm')[0].reset();
                $('#sourceForm').attr('action', "{{ route('source.store') }}");
                $('#methodField').html('');
                $('#name').removeClass('is-invalid');
                $('#submitBtn').html('<i class="bi bi-save me-1"></i> Save Source');
                $('#cancelBtn').addClass('d-none');
            }

            $(document).on('click', '.delete-btn', function() {
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Source?',
                    text: 'This source will be removed from the master list.',
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
