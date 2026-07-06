@extends('layouts.app')

@push('title')
    Support Ticket Details
@endpush

@section('content')
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

    <div class="container-fluid mt-4 transaction-page support-management-page">
        <div class="transaction-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="transaction-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Ticket #{{ $support->id }}</span>
                        <h3 class="fw-bold mb-1 text-dark">Support Ticket Details</h3>
                        <p class="text-muted mb-0 small">Review the request, send a reply, and update ticket status.</p>
                    </div>
                </div>

                <a href="{{ route('support.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="transaction-card h-100">
                    <div class="transaction-history-head">
                        <div class="d-flex align-items-center gap-3">
                            <span class="transaction-section-title-icon">
                                <i class="bi bi-person-lines-fill"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-1">Ticket Information</h5>
                                <small class="text-muted">Source and request details.</small>
                            </div>
                        </div>

                        @if ($support->status == 'Pending')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                        @elseif($support->status == 'Resolved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Resolved</span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">In Progress</span>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="d-flex align-items-center gap-3 border rounded-3 p-3 mb-3 bg-light">
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

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="border rounded-3 p-3 bg-light h-100">
                                    <small class="text-muted d-block mb-1">Subject</small>
                                    <div class="fw-bold text-dark">{{ $support->query }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded-3 p-3 bg-light h-100">
                                    <small class="text-muted d-block mb-1">Description</small>
                                    <div class="text-dark">{{ $support->description }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 bg-light h-100">
                                    <small class="text-muted d-block mb-1">Created Date</small>
                                    <div class="fw-semibold text-dark">{{ $support->created_at->format('d M, Y') }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 bg-light h-100">
                                    <small class="text-muted d-block mb-1">Created Time</small>
                                    <div class="fw-semibold text-dark">{{ $support->created_at->format('h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="transaction-card h-100">
                    <div class="transaction-history-head">
                        <div class="d-flex align-items-center gap-3">
                            <span class="transaction-section-title-icon">
                                <i class="bi bi-send"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-1">Admin Response</h5>
                                <small class="text-muted">Reply to the ticket and update progress.</small>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        @if ($support->status == 'Resolved')
                            <div class="alert alert-success border-0 rounded-3 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill fs-5"></i>
                                    <div>
                                        <div class="fw-bold">Ticket Resolved</div>
                                        <small>This ticket is closed. The reply is shown below.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-3 p-4 bg-light">
                                <small class="text-muted d-block mb-2">Admin Reply</small>
                                <div class="text-dark">{{ $support->reply ?? 'No reply available.' }}</div>
                            </div>
                        @else
                            <form action="{{ route('support.reply', $support->id) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Reply Message</label>
                                    <textarea name="reply" rows="8" class="form-control @error('reply') is-invalid @enderror"
                                        placeholder="Write your reply here...">{{ old('reply', $support->reply) }}</textarea>
                                    @error('reply')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Update Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="In-Progress"
                                            {{ old('status', $support->status) == 'In-Progress' ? 'selected' : '' }}>
                                            In Progress
                                        </option>
                                        <option value="Resolved"
                                            {{ old('status', $support->status) == 'Resolved' ? 'selected' : '' }}>
                                            Resolved
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('support.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="bi bi-send me-1"></i>
                                        Submit Reply
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
