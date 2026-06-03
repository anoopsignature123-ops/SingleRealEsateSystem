@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-arrow-repeat me-2 text-success"></i>
                            Plot Change Management
                        </h3>

                        <p class="text-muted mb-0 small">
                            Change customer plot and recalculate payment adjustment.
                        </p>
                    </div>

                    <a href="{{ route('plot-transfer.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back
                    </a>

                </div>

            </div>
        </div>

        {{-- MAIN FORM --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <form id="plotChangeForm">
                    @csrf
                    <input type="hidden" id="plotSaleDetailId">
                    <input type="hidden" id="newProjectId">
                    <input type="hidden" id="newBlockId">
                    <input type="hidden" id="newPlotDetailId">
                    {{-- OLD PLOT SELECTION --}}
                    <div class="border-bottom pb-3 mb-4">
                        <h5 class="fw-bold mb-1">Current Plot Selection</h5>
                        <small class="text-muted">Select the customer's existing booked plot.</small>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Project</label>
                            <select id="oldProjectId" class="form-select">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Block</label>
                            <select id="oldBlockId" class="form-select">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Booked Plot</label>
                            <select id="oldPlotId" class="form-select">
                                <option value="">Select Plot</option>
                            </select>
                        </div>
                    </div>

                    {{-- OLD DETAILS --}}
                    <div id="oldPlotDetailsCard" class="card border shadow-sm rounded-4 mb-4 d-none">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="fw-bold mb-0">Current Booking & Payment Details</h6>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Booking ID</label>
                                    <input type="text" id="bookingCode" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Customer ID</label>
                                    <input type="text" id="customerCode" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Customer Name</label>
                                    <input type="text" id="customerName" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Old Plot</label>
                                    <input type="text" id="oldPlotInfo" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Old Total Cost</label>
                                    <input type="text" id="oldTotalCost" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Total Paid</label>
                                    <input type="text" id="totalPaidAmount"
                                        class="form-control bg-light text-success fw-bold" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Old Due Amount</label>
                                    <input type="text" id="oldDueAmount"
                                        class="form-control bg-light text-danger fw-bold" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- NEW PLOT SELECTION --}}
                    <div id="newPlotSection" class="d-none">
                        <div class="border-bottom pb-3 mb-4">
                            <h5 class="fw-bold mb-1">New Plot Selection</h5>
                            <small class="text-muted">Select available plot for replacement.</small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">New Project</label>
                                <select id="newProjectSelect" class="form-select">
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">New Block</label>
                                <select id="newBlockSelect" class="form-select">
                                    <option value="">Select Block</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Available Plot</label>
                                <select id="newPlotSelect" class="form-select">
                                    <option value="">Select Available Plot</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- NEW PLOT DETAILS --}}
                    <div id="newPlotDetailsCard" class="card border shadow-sm rounded-4 mb-4 d-none">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="fw-bold mb-0">New Plot Calculation</h6>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">New Plot</label>
                                    <input type="text" id="newPlotInfo" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Plot Area</label>
                                    <input type="text" id="newPlotArea" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Plot Rate</label>
                                    <input type="text" id="newPlotRate" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Plot Cost</label>
                                    <input type="text" id="newPlotCost" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">PLC Amount</label>
                                    <input type="text" id="newPlcAmount" class="form-control bg-light" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">New Total Cost</label>
                                    <input type="text" id="newTotalCost" class="form-control bg-light fw-bold"
                                        readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">New Due Amount</label>
                                    <input type="text" id="newDueAmount"
                                        class="form-control bg-light text-danger fw-bold" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Difference Amount</label>
                                    <input type="text" id="differenceAmount" class="form-control bg-light fw-bold"
                                        readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted fw-bold text-uppercase">Change Date</label>
                                    <input type="date" id="changeDate" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Change Reason</label>
                                    <textarea id="changeReason" rows="3" class="form-control" placeholder="Enter plot change reason"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Remark</label>
                                    <textarea id="remark" rows="2" class="form-control" placeholder="Enter remark"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" id="plotChangeBtn" class="btn btn-success px-4">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Change Plot
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        {{-- HISTORY --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom">
                <h4 class="fw-bold mb-1">Plot Change History</h4>
                <small class="text-muted">All plot change records.</small>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="plotChangeHistoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Old Plot</th>
                                <th>New Plot</th>
                                <th>Paid</th>
                                <th>Old Due</th>
                                <th>New Due</th>
                                <th>Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($histories as $key => $history)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $history->plotSaleDetail?->booking_code ?? '-' }}</td>
                                    <td>
                                        {{ $history->customerBooking?->customer_code ?? '-' }}
                                        <br>
                                        <small>{{ $history->customerBooking?->primaryDetail?->name ?? '-' }}</small>
                                    </td>
                                    <td>
                                        {{ $history->oldProject?->name ?? '-' }} /
                                        {{ $history->oldBlock?->block ?? '-' }} /
                                        {{ $history->oldPlot?->plot_number ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $history->newProject?->name ?? '-' }} /
                                        {{ $history->newBlock?->block ?? '-' }} /
                                        {{ $history->newPlot?->plot_number ?? '-' }}
                                    </td>
                                    <td class="text-success fw-bold">
                                        ₹{{ number_format((float) $history->total_paid_amount, 2) }}</td>
                                    <td class="text-danger fw-bold">
                                        ₹{{ number_format((float) $history->old_due_amount, 2) }}</td>
                                    <td class="text-danger fw-bold">
                                        ₹{{ number_format((float) $history->new_due_amount, 2) }}</td>
                                    <td>{{ $history->change_date ? $history->change_date->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $history->change_reason ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        No plot change history found.
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

@include('plot_change.scripts')

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#plotChangeHistoryTable tbody tr td').attr('colspan') === undefined) {
                $('#plotChangeHistoryTable').DataTable({
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
