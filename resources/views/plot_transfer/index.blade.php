@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">
                            <i class="bi bi-arrow-left-right me-2 text-success"></i>
                            Plot Transfer Management
                        </h3>
                        <p class="text-muted small mb-0">
                            Transfer plot ownership from one customer to another.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('payment-transfer.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-cash-stack me-2"></i>
                            Payment Transfer
                        </a>
                        <a href="{{ route('plot-change.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-house-gear me-2"></i>
                            Plot Change
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRANSFER FORM --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <form id="plotTransferForm">
                    @csrf

                    <input type="hidden" id="customerBookingId">
                    <input type="hidden" id="plotSaleDetailId">

                    <div class="border-bottom pb-3 mb-4">
                        <h5 class="fw-bold mb-1">Plot Selection</h5>
                        <small class="text-muted">Choose the current booked plot for transfer.</small>
                    </div>

                    {{-- FILTERS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Site Name</label>
                            <select id="projectId" class="form-select">
                                <option value="">Select Site</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Block</label>
                            <select id="blockId" class="form-select">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Plot Number</label>
                            <select id="plotSaleId" class="form-select">
                                <option value="">Select Plot</option>
                            </select>
                        </div>
                    </div>

                    {{-- CURRENT CUSTOMER INFO --}}
                    <div class="bg-light border rounded-4 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Current Owner Details</h6>
                            <span class="badge bg-secondary">Auto Filled</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Booking ID</label>
                                <input type="text" id="bookingCode" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Customer ID</label>
                                <input type="text" id="customerCode" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Customer Name</label>
                                <input type="text" id="customerName" class="form-control bg-white" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- BOOKING DETAILS --}}
                    <div id="bookingDetailsCard" class="card border shadow-sm rounded-4 mb-4 d-none">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="fw-bold mb-0">Booking & Payment Details</h6>
                        </div>

                        <div class="card-body p-0" id="bookingDetailsContent"></div>
                    </div>

                    {{-- TRANSFER SECTION --}}
                    <div id="transferSection" class="card border shadow-sm rounded-4 d-none">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-arrow-left-right me-2 text-success"></i>
                                Transfer Ownership
                            </h6>
                            <small class="text-muted">
                                Select the new customer and enter transfer reason.
                            </small>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Current Owner</label>
                                    <input type="text" id="currentOwner" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Transfer To Customer <span class="text-danger">*</span>
                                    </label>
                                    <select id="newCustomerBookingId" class="form-select">
                                        <option value="">Select Customer</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Transfer Reason</label>
                                    <textarea id="transferReason" rows="3" class="form-control" placeholder="Enter transfer reason"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" id="transferBtn" class="btn btn-success px-4">
                                        <i class="bi bi-arrow-left-right me-1"></i>
                                        Transfer Plot
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        {{-- TRANSFER HISTORY --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-bottom">
                <div>
                    <h4 class="fw-bold mb-1">Transfer History</h4>
                    <small class="text-muted">All plot ownership transfer records.</small>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="transferHistoryTable">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Project / Block / Plot</th>
                                <th>Old Customer</th>
                                <th>New Customer</th>
                                <th>Transfer Date</th>
                                <th>Reason</th>
                                <th>Created By</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($histories as $key => $history)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="badge bg-success">
                                            {{ $history->plotSaleDetail?->booking_code ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $history->plotSaleDetail?->project?->name ?? '-' }}
                                        </div>
                                        <small class="text-muted">
                                            Block:
                                            {{ $history->plotSaleDetail?->block?->block ?? '-' }}
                                            |
                                            Plot:
                                            {{ $history->plotSaleDetail?->plotDetail?->plot_number ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $history->old_customer_code ?? '-' }}
                                        </span>
                                        <br>
                                        <small>{{ $history->old_customer_name ?? '-' }}</small>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $history->new_customer_code ?? '-' }}
                                        </span>
                                        <br>
                                        <small>{{ $history->new_customer_name ?? '-' }}</small>
                                    </td>

                                    <td>
                                        {{ $history->transfer_date ? $history->transfer_date->format('d-m-Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $history->transfer_reason ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $history->createdBy?->name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No transfer history found.
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

@include('plot_transfer.scripts')

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#transferHistoryTable tbody tr td').attr('colspan') === undefined) {
                $('#transferHistoryTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    order: [
                        [0, 'desc']
                    ],
                });
            }
        });
    </script>
@endpush
