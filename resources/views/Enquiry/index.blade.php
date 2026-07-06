@extends('layouts.app')

@push('title')
    Lead Enquiry Management
@endpush

@section('content')
    @php
        $totalEnquiries = $enquiries->count();
        $todayFollowups = $enquiries->filter(fn ($item) => $item->followup_date && \Carbon\Carbon::parse($item->followup_date)->isToday())->count();
        $upcomingFollowups = $enquiries->filter(fn ($item) => $item->followup_date && \Carbon\Carbon::parse($item->followup_date)->isFuture())->count();
        $unassignedLeads = $enquiries->whereNull('associate_id')->count();
    @endphp

    <div class="container-fluid mt-4 transaction-page lead-management-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Lead Management</span>
                        <h3 class="fw-bold mb-1 text-dark">New Enquiry</h3>
                        <p class="text-muted mb-0 small">Capture customer enquiries and track follow-up dates.</p>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalEnquiries }} Leads</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-people"></i></span>
                    <div>
                        <small>Total Leads</small>
                        <strong>{{ $totalEnquiries }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-calendar-check"></i></span>
                    <div>
                        <small>Today's Followups</small>
                        <strong>{{ $todayFollowups }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-calendar2-week"></i></span>
                    <div>
                        <small>Upcoming Followups</small>
                        <strong>{{ $upcomingFollowups }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-person-dash"></i></span>
                    <div>
                        <small>Unassigned Leads</small>
                        <strong>{{ $unassignedLeads }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @can('new-enquiry-modify')
            <div class="transaction-card mb-4">
                <div class="transaction-history-head">
                    <div class="d-flex align-items-center gap-3">
                        <span class="transaction-section-title-icon">
                            <i class="bi bi-plus-circle"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-1" id="formTitle">Add New Enquiry</h5>
                            <small class="text-muted">Enter lead details, assignment, source and follow-up plan.</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-card-body">
                    @include('enquiry.form')
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
                        <h5 class="fw-bold mb-1">Enquiry Records</h5>
                        <small class="text-muted">View, edit and delete captured leads.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalEnquiries }} Records</span>
            </div>

            <div class="transaction-table-wrap">
                <table class="table transaction-table align-middle mb-0" id="enquiryTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Associate</th>
                            <th>Source</th>
                            <th>Lead Type</th>
                            <th>Budget</th>
                            <th>Follow-up</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enquiries as $key => $enquiry)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $enquiry->customer_name }}</div>
                                    <small class="text-muted">{{ $enquiry->email ?: 'No email' }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $enquiry->mobile_number }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $enquiry->associate?->associate_name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $enquiry->source?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        {{ $enquiry->enquiryType?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $enquiry->budget ?: 'N/A' }}</td>
                                <td>
                                    @if ($enquiry->followup_date)
                                        <div class="fw-semibold text-dark">
                                            {{ \Carbon\Carbon::parse($enquiry->followup_date)->format('d M, Y') }}
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $enquiry->created_at ? $enquiry->created_at->format('d M, Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @can('new-enquiry-modify')
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success editBtn"
                                                data-id="{{ $enquiry->id }}">
                                                <i class="bi bi-pencil-square me-1"></i>
                                                Edit
                                            </button>

                                            <form action="{{ route('enquiry.destroy', $enquiry->id) }}" method="POST"
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
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-person-lines-fill fs-1 d-block mb-2 text-muted"></i>
                                    No enquiries found.
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
            const hasRecords = {{ $totalEnquiries > 0 ? 'true' : 'false' }};

            if (hasRecords) {
                $('#enquiryTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    columnDefs: [{
                        orderable: false,
                        targets: [9]
                    }],
                    language: {
                        search: "",
                        searchPlaceholder: "Search enquiries..."
                    }
                });
            }

            $('.editBtn').click(function() {
                const id = $(this).data('id');
                const editUrl = "{{ route('enquiry.edit', '__ID__') }}".replace('__ID__', id);
                const updateUrl = "{{ route('enquiry.update', '__ID__') }}".replace('__ID__', id);

                $('.form-control, .form-select').removeClass('is-invalid');

                $.get(editUrl, function(data) {
                    $('#formTitle').text('Edit Enquiry');
                    $('#customer_name').val(data.customer_name);
                    $('#mobile_number').val(data.mobile_number);
                    $('#email').val(data.email);
                    $('#dob').val(data.dob ? data.dob.substring(0, 10) : '');
                    $('#associate_id').val(data.associate_id);
                    $('#source_id').val(data.source_id);
                    $('#enquiry_types_id').val(data.enquiry_types_id);
                    $('#state').val(data.state);
                    $('#city').val(data.city);
                    $('#plot_size').val(data.plot_size);
                    $('#budget').val(data.budget);
                    $('#location').val(data.location);
                    $('#followup_date').val(data.followup_date ? data.followup_date.substring(0, 10) : '');

                    $('#enquiryForm').attr('action', updateUrl);
                    $('#methodField').html('@method('PUT')');
                    $('#submitBtn').html('<i class="bi bi-save me-1"></i> Update Enquiry');
                    $('#cancelBtn').removeClass('d-none');
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                });
            });

            $('#cancelBtn').click(function() {
                resetForm();
            });

            function resetForm() {
                $('#formTitle').text('Add New Enquiry');
                $('#enquiryForm')[0].reset();
                $('#enquiryForm').attr('action', "{{ route('enquiry.store') }}");
                $('#methodField').html('');
                $('.form-control, .form-select').removeClass('is-invalid');
                $('#submitBtn').html('<i class="bi bi-save me-1"></i> Save Enquiry');
                $('#cancelBtn').addClass('d-none');
            }

            $(document).on('click', '.delete-btn', function() {
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Enquiry?',
                    text: 'This lead enquiry will be removed permanently.',
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
