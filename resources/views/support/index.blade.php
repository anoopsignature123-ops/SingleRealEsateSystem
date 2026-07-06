@extends('layouts.app')

@push('title')
    Support Management
@endpush

@section('content')
    @php
        $totalTickets = $supports->count();
        $pendingTickets = $supports->where('status', 'Pending')->count();
        $progressTickets = $supports->where('status', 'In-Progress')->count();
        $resolvedTickets = $supports->where('status', 'Resolved')->count();
    @endphp

    <div class="container-fluid mt-4 transaction-page support-management-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-headset"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Help Desk</span>
                        <h3 class="fw-bold mb-1 text-dark">Support Management</h3>
                        <p class="text-muted mb-0 small">Review customer and associate tickets, reply, and track status.</p>
                    </div>
                </div>

                <span class="transaction-count">{{ $pendingTickets }} Pending</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-ticket-detailed"></i></span>
                    <div>
                        <small>Total Tickets</small>
                        <strong>{{ $totalTickets }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                    <div>
                        <small>Pending</small>
                        <strong>{{ $pendingTickets }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-arrow-repeat"></i></span>
                    <div>
                        <small>In Progress</small>
                        <strong>{{ $progressTickets }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="role-stat-card">
                    <span class="role-stat-icon"><i class="bi bi-check-circle"></i></span>
                    <div>
                        <small>Resolved</small>
                        <strong>{{ $resolvedTickets }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="transaction-card transaction-history-card support-table-card mb-4">
            <div class="transaction-history-head">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-section-title-icon">
                        <i class="bi bi-inbox"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-1">Support Tickets</h5>
                        <small class="text-muted">Open each ticket to reply or update its status.</small>
                    </div>
                </div>

                <span class="transaction-count">{{ $totalTickets }} Records</span>
            </div>

            <div class="transaction-table-wrap">
                <table id="supportTable" class="table transaction-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Raised By</th>
                            <th>Ticket</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supports as $support)
                            @php
                                $raisedByName = $support->associate?->associate_name
                                    ?? $support->customerBooking?->primaryDetail?->name
                                    ?? $support->customerBooking?->customer_name
                                    ?? '-';
                                $raisedByCode = $support->associate?->associate_id
                                    ?? $support->customerBooking?->customer_code
                                    ?? '';
                                $raisedByType = $support->associate_id ? 'Associate' : 'Customer';
                                $initials = strtoupper(substr($raisedByName, 0, 2));
                            @endphp

                            <tr>
                                <td class="fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if (!empty($support->associate->photo))
                                            <img src="{{ asset('storage/' . $support->associate->photo) }}"
                                                alt="{{ $raisedByName }}" class="staff-avatar"
                                                onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                        @else
                                            <span class="role-avatar">{{ $initials }}</span>
                                        @endif

                                        <div>
                                            <div class="fw-bold text-dark">{{ $raisedByName }}</div>
                                            <small class="text-muted">
                                                {{ $raisedByType }}{{ $raisedByCode ? ' - ' . $raisedByCode : '' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $support->query }}</div>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($support->description, 70) }}
                                    </small>
                                </td>
                                <td>
                                    @if ($support->status == 'Pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            Pending
                                        </span>
                                    @elseif($support->status == 'Resolved')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Resolved
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            In Progress
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $support->created_at->format('d M, Y') }}</div>
                                    <small class="text-muted">{{ $support->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('support.detail', $support->id) }}"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-eye me-1"></i>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                    No support tickets found.
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
            const hasRecords = {{ $totalTickets > 0 ? 'true' : 'false' }};

            if (hasRecords) {
                $('#supportTable').DataTable({
                    pageLength: 10,
                    ordering: true,
                    responsive: true,
                    lengthMenu: [5, 10, 25, 50],
                    columnDefs: [{
                        orderable: false,
                        targets: [5]
                    }],
                    language: {
                        search: "",
                        searchPlaceholder: "Search tickets..."
                    }
                });
            }
        });
    </script>
@endpush
