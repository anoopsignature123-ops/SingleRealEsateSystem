@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-1 text-dark">Plot Registry</h3>
                <p class="text-muted mb-0 small">
                    Select booked plot and create registry details.
                </p>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('plot-registry.store') }}" id="registryForm">
                    @csrf
                    <input type="hidden" name="customer_booking_id" id="customer_booking_db_id">
                    <div class="border-bottom pb-3 mb-4">
                        <h5 class="fw-bold mb-1">Plot Selection</h5>
                        <small class="text-muted">Select booked plot for registry.</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Site Name <span class="text-danger">*</span>
                            </label>
                            <select id="project_id" name="project_id"
                                class="form-select @error('project_id') is-invalid @enderror">
                                <option value="">Select Site</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Block <span class="text-danger">*</span>
                            </label>
                            <select id="block_id" name="block_id"
                                class="form-select @error('block_id') is-invalid @enderror">
                                <option value="">Select Block</option>
                            </select>
                            @error('block_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Plot No <span class="text-danger">*</span>
                            </label>
                            <select id="plot_id" name="plot_detail_id"
                                class="form-select @error('plot_detail_id') is-invalid @enderror">
                                <option value="">Select Plot</option>
                            </select>
                            @error('plot_detail_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-light border rounded-4 p-3 mt-4">
                        <h6 class="fw-bold mb-3">Customer Details</h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Booking ID</label>
                                <input type="text" id="booking_id" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Customer ID</label>
                                <input type="text" id="customer_id" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Customer Name</label>
                                <input type="text" id="customer_name" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Total Cost</label>
                                <input type="text" id="total_cost" class="form-control bg-white" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Total Paid</label>
                                <input type="text" id="total_paid" class="form-control bg-white text-success fw-bold"
                                    readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="small text-muted fw-bold text-uppercase">Due Amount</label>
                                <input type="text" id="due_amount" class="form-control bg-white text-danger fw-bold"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="border-bottom pb-3 mt-4 mb-4">
                        <h5 class="fw-bold mb-1">Registry Details</h5>
                        <small class="text-muted">Enter registry document details.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Gata Number</label>
                            <input type="text" name="gata_number"
                                class="form-control @error('gata_number') is-invalid @enderror"
                                placeholder="Enter gata number" value="{{ old('gata_number') }}">
                            @error('gata_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Seller Name</label>
                            <input type="text" name="seller_name"
                                class="form-control @error('seller_name') is-invalid @enderror"
                                placeholder="Enter seller name" value="{{ old('seller_name') }}">
                            @error('seller_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Registry No</label>
                            <input type="text" name="register_no"
                                class="form-control @error('register_no') is-invalid @enderror"
                                placeholder="Enter registry no" value="{{ old('register_no') }}">
                            @error('register_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Registry Date</label>
                            <input type="date" name="register_date"
                                class="form-control @error('register_date') is-invalid @enderror"
                                value="{{ old('register_date', date('Y-m-d')) }}">
                            @error('register_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="paymentSection" class="mt-4 d-none">
                        <h5 class="fw-bold mb-3">Payment History</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Receipt</th>
                                        <th>Amount</th>
                                        <th>Pay Date</th>
                                        <th>Mode</th>
                                        <th>Status</th>
                                        <th>Category</th>
                                        <th>Cheque No</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa fa-save me-1"></i>
                            Save Registry
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Registry History</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="registryHistoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Project / Block / Plot</th>
                                <th>Gata No</th>
                                <th>Seller</th>
                                <th>Registry No</th>
                                <th>Registry Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($registries as $key => $registry)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        {{ $registry->plotDetail?->plotSaleDetail?->booking_code ?? ($registry->customerBooking?->booking_code ?? '-') }}
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $registry->customerBooking?->customer_code ?? '-' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $registry->customerBooking?->primaryDetail?->name ?? ($registry->customerBooking?->customer_name ?? '-') }}
                                        </small>
                                    </td>

                                    <td>
                                        <strong>{{ $registry->project?->name ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Block:
                                            {{ $registry->block?->block ?? '-' }}
                                            |
                                            Plot:
                                            {{ $registry->plotDetail?->plot_number ?? '-' }}
                                        </small>
                                    </td>

                                    <td>{{ $registry->gata_number ?? '-' }}</td>
                                    <td>{{ $registry->seller_name ?? '-' }}</td>
                                    <td>{{ $registry->register_no ?? '-' }}</td>

                                    <td>
                                        {{ $registry->register_date ? \Carbon\Carbon::parse($registry->register_date)->format('d-m-Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No registry history found.
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

            clearAll();

            $('#project_id').on('change', function() {
                let projectId = $(this).val();

                resetBlocks();
                resetPlots();
                clearBooking();

                if (!projectId) return;

                $.get("{{ route('plot-registry.blocks', ':id') }}".replace(':id', projectId), function(
                res) {
                    $.each(res, function(index, block) {
                        $('#block_id').append(`
                    <option value="${block.id}">
                        ${block.block}
                    </option>
                `);
                    });
                });
            });

            $('#block_id').on('change', function() {
                let blockId = $(this).val();

                resetPlots();
                clearBooking();

                if (!blockId) return;

                $.get("{{ route('plot-registry.plots', ':id') }}".replace(':id', blockId), function(res) {
                    if (res.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Plot Found',
                            text: 'Is block me registry ke liye koi booked plot nahi mila.'
                        });
                    }

                    $.each(res, function(index, plot) {
                        $('#plot_id').append(`
                    <option value="${plot.id}">
                        ${plot.plot_number}
                    </option>
                `);
                    });
                });
            });

            $('#plot_id').on('change', function() {
                let plotId = $(this).val();

                clearBooking();

                if (!plotId) return;

                $.get("{{ route('plot-registry.booking', ':id') }}".replace(':id', plotId), function(res) {
                    if (!res.status) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Booking Not Found',
                            text: res.message || 'Booking details not found.'
                        });
                        return;
                    }

                    $('#customer_booking_db_id').val(res.booking_db_id);
                    $('#booking_id').val(res.booking_id);
                    $('#customer_id').val(res.customer_id);
                    $('#customer_name').val(res.customer_name);

                    $('#total_cost').val('₹' + res.total_cost);
                    $('#total_paid').val('₹' + res.total_paid);
                    $('#due_amount').val('₹' + res.due_amount);

                    let html = '';

                    if (res.payment_history.length > 0) {
                        $.each(res.payment_history, function(index, payment) {
                            html += `
                        <tr>
                            <td>${payment.receipt_no}</td>
                            <td class="text-success fw-semibold">₹${payment.amount}</td>
                            <td>${payment.date}</td>
                            <td>${payment.mode}</td>
                            <td>${payment.status}</td>
                            <td>${payment.category}</td>
                            <td>${payment.cheque_no}</td>
                        </tr>
                    `;
                        });
                    } else {
                        html = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            No payment history found.
                        </td>
                    </tr>
                `;
                    }

                    $('#paymentTableBody').html(html);
                    $('#paymentSection').removeClass('d-none');
                });
            });

            if ($('#registryHistoryTable tbody tr td').attr('colspan') === undefined) {
                $('#registryHistoryTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }

            function resetBlocks() {
                $('#block_id').html('<option value="">Select Block</option>');
            }

            function resetPlots() {
                $('#plot_id').html('<option value="">Select Plot</option>');
            }

            function clearBooking() {
                $('#customer_booking_db_id').val('');
                $('#booking_id').val('');
                $('#customer_id').val('');
                $('#customer_name').val('');
                $('#total_cost').val('');
                $('#total_paid').val('');
                $('#due_amount').val('');
                $('#paymentTableBody').html('');
                $('#paymentSection').addClass('d-none');
            }

            function clearAll() {
                resetBlocks();
                resetPlots();
                clearBooking();
            }
        });
    </script>
@endpush
