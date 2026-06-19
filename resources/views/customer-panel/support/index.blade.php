@extends('layouts.app')

@section('content')
    <div class="container-fluid customer-panel-page">
        <div class="customer-page-header">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-headset text-success me-2"></i>
                    Support Center
                </h4>
                <p class="mb-0">Raise support tickets and track admin replies.</p>
            </div>

            <div class="d-flex gap-3 flex-wrap">
                <div class="customer-info-card py-3 px-4">
                    <small>Total Tickets</small>
                    <strong>{{ $enquiries->count() }}</strong>
                </div>
                <div class="customer-info-card py-3 px-4">
                    <small>Open Tickets</small>
                    <strong class="text-warning">{{ $enquiries->where('status', '!=', 'Resolved')->count() }}</strong>
                </div>
                <div class="customer-info-card py-3 px-4">
                    <small>Closed Tickets</small>
                    <strong class="text-success">{{ $enquiries->where('status', 'Resolved')->count() }}</strong>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="customer-section-card">
                    <div class="customer-section-header d-block">
                        <h5 class="mb-1">
                            <i class="bi bi-pencil-square text-success me-2"></i>
                            Create Ticket
                        </h5>
                        <p class="mb-0">Submit your issue or query.</p>
                    </div>

                    <div class="customer-section-body">
                        <form action="{{ route('customer-panel.support.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text" name="query"
                                    class="form-control form-control-lg rounded-4 @error('query') is-invalid @enderror"
                                    value="{{ old('query') }}" placeholder="Enter your issue subject" required>
                                @error('query')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" rows="7" class="form-control rounded-4 @error('description') is-invalid @enderror"
                                    placeholder="Explain your issue properly..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg rounded-4 fw-semibold shadow-sm">
                                    <i class="bi bi-send me-2"></i>
                                    Submit Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="customer-section-card">
                    <div class="customer-section-header d-block">
                        <h5 class="mb-1">
                            <i class="bi bi-clock-history text-success me-2"></i>
                            Ticket History
                        </h5>
                        <p class="mb-0">View all support conversations.</p>
                    </div>

                    <div class="customer-section-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover customer-table w-100" id="customerSupportTable">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Admin Reply</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($enquiries as $item)
                                        <tr>
                                            <td style="min-width: 230px;">
                                                <div class="fw-bold text-dark">{{ ucfirst($item->query) }}</div>
                                                <small class="text-muted">{{ Str::limit($item->description, 70) }}</small>
                                            </td>
                                            <td style="min-width: 130px;">
                                                @if ($item->status == 'Pending')
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">Pending</span>
                                                @elseif($item->status == 'Resolved')
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Resolved</span>
                                                @else
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">In-Progress</span>
                                                @endif
                                            </td>
                                            <td style="min-width: 260px;">
                                                @if ($item->reply)
                                                    <div class="bg-light rounded-4 p-3 border">
                                                        <div class="fw-semibold text-success mb-1">
                                                            <i class="bi bi-reply-fill me-1"></i>
                                                            Admin Reply
                                                        </div>
                                                        <small class="text-dark">{{ $item->reply }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No reply yet</span>
                                                @endif
                                            </td>
                                            <td style="min-width: 120px;">
                                                <div class="fw-semibold">{{ $item->created_at->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $item->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    No support tickets found
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#customerSupportTable').DataTable({
                order: [
                    [3, 'desc']
                ],
                pageLength: 5,
                lengthMenu: [5, 10, 25],
                scrollX: true
            });
        });
    </script>
@endpush
