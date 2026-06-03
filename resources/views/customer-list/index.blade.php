@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-1 text-dark">Customer List</h3>
                <p class="text-muted mb-0 small">
                    View all customers and their booking details
                </p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="customerListTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer ID</th>
                                <th>Reference Customer</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th>Total Bookings</th>
                                <th>Booked Plots</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customers as $key => $customer)
                                @php
                                    $primary = $customer->primaryDetail;
                                    $contact = $primary?->correspondenceDetail;

                                    $address =
                                        $primary?->permanent_address ??
                                        ($primary?->city ? $primary->city . ', ' . $primary->state : 'N/A');

                                    $parentCustomer = $customer->parentCustomer;

                                    $plots = $customer->booked_plots ?? ($customer->plotSaleDetails ?? collect());
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        {{ $customer->customer_code ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @if ($parentCustomer)
                                            <span class="badge bg-info">
                                                {{ $parentCustomer->customer_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">Self</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ ucfirst($primary?->name ?? 'N/A') }}
                                    </td>

                                    <td>
                                        {{ $address }}
                                    </td>

                                    <td>
                                        {{ $contact?->telephone_no ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $contact?->email ?? 'N/A' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary px-3 py-2">
                                            {{ $plots->count() }}
                                            {{ $plots->count() > 1 ? 'Plots' : 'Plot' }}
                                        </span>
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#plotModal{{ $customer->id }}">
                                            View Plots
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No customers found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- MODALS --}}
        @foreach ($customers as $customer)
            @php
                $primary = $customer->primaryDetail;

                $plots = $customer->booked_plots ?? ($customer->plotSaleDetails ?? collect());
            @endphp

            <div class="modal fade" id="plotModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4 shadow-lg">

                        <div class="modal-header bg-success text-white">
                            <div>
                                <h5 class="modal-title mb-0">
                                    Booked Plot Details
                                </h5>
                                <small>
                                    {{ $customer->customer_code ?? 'N/A' }}
                                    -
                                    {{ $primary?->name ?? 'N/A' }}
                                </small>
                            </div>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-3">
                            @if ($plots->count() > 0)
                                <div class="table-responsive modal-table-scroll">
                                    <table class="table table-bordered table-sm align-middle mb-0">
                                        <thead class="table-success">
                                            <tr>
                                                <th>#</th>
                                                <th>Booking ID</th>
                                                <th>Project</th>
                                                <th>Block</th>
                                                <th>Plot No</th>
                                                <th>Plot Area</th>
                                                <th>Plot Rate</th>
                                                <th>Total Cost</th>
                                                <th>Booking Date</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($plots as $plotKey => $plot)
                                                <tr>
                                                    <td>{{ $plotKey + 1 }}</td>

                                                    <td>
                                                        <span class="badge bg-success">
                                                            {{ $plot->booking_code ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ $plot->project->name ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->block->block ?? 'N/A' }}
                                                    </td>

                                                    <td class="fw-bold">
                                                        {{ $plot->plotDetail->plot_number ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->plot_area ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        ₹{{ number_format((float) ($plot->plot_rate ?? 0), 2) }}
                                                    </td>

                                                    <td>
                                                        ₹{{ number_format((float) ($plot->total_plot_cost ?? 0), 2) }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->booking_date ? \Carbon\Carbon::parse($plot->booking_date)->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    No booked plot found.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('styles')
    <style>
        .modal-xl {
            max-width: 95%;
        }

        .modal-table-scroll {
            max-height: 65vh;
            overflow: auto;
        }

        .modal-table-scroll table {
            white-space: nowrap;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#customerListTable tbody tr td').attr('colspan') == undefined) {
                $('#customerListTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }
        });
    </script>
@endpush
