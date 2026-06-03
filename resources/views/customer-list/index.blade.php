@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Page Header --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            Customer List
                        </h3>
                        <p class="text-muted mb-0 small">
                            View customers and their booked plot summary.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer Table --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerListTable">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer ID</th>
                                <th>Reference</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Contact No</th>
                                <th>Email</th>
                                <th class="text-center">Bookings</th>
                                <th class="text-center">Plots</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customers as $key => $customer)
                                @php
                                    $primary = $customer->primaryDetail;
                                    $contact = $primary?->correspondenceDetail;

                                    $address =
                                        $primary?->permanent_address ??
                                        ($primary?->city
                                            ? $primary->city . ', ' . $primary->state
                                            : 'N/A');

                                    $parentCustomer = $customer->parentCustomer;
                                    $plots = $customer->booked_plots ?? ($customer->plotSaleDetails ?? collect());
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $customer->customer_code ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($parentCustomer)
                                            <span class="badge bg-light text-dark border">
                                                {{ $parentCustomer->customer_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">Self</span>
                                        @endif
                                    </td>

                                    <td class="fw-semibold">
                                        {{ ucfirst($primary?->name ?? 'N/A') }}
                                    </td>

                                    <td style="max-width: 260px;">
                                        <span class="text-muted">
                                            {{ $address }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $contact?->telephone_no ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $contact?->email ?? 'N/A' }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $plots->count() }}
                                            {{ $plots->count() > 1 ? 'Plots' : 'Plot' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
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

        {{-- Modals --}}
        @foreach ($customers as $customer)
            @php
                $primary = $customer->primaryDetail;
                $plots = $customer->booked_plots ?? ($customer->plotSaleDetails ?? collect());
            @endphp

            <div class="modal fade" id="plotModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 rounded-4 shadow">

                        <div class="modal-header bg-white border-bottom">
                            <div>
                                <h5 class="modal-title fw-bold mb-1">
                                    Booked Plot Details
                                </h5>
                                <small class="text-muted">
                                    {{ $customer->customer_code ?? 'N/A' }}
                                    -
                                    {{ $primary?->name ?? 'N/A' }}
                                </small>
                            </div>

                            <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">

                            @if ($plots->count() > 0)
                                <div class="table-responsive modal-table-scroll">
                                    <table class="table table-bordered table-hover align-middle mb-0">

                                        <thead class="table-light">
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
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $plot->booking_code ?? 'N/A' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{ $plot->project->name ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->block->block ?? 'N/A' }}
                                                    </td>

                                                    <td class="fw-semibold">
                                                        {{ $plot->plotDetail->plot_number ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->plot_area ?? 'N/A' }}
                                                    </td>

                                                    <td>
                                                        ₹{{ number_format((float) ($plot->plot_rate ?? 0), 2) }}
                                                    </td>

                                                    <td class="fw-semibold">
                                                        ₹{{ number_format((float) ($plot->total_plot_cost ?? 0), 2) }}
                                                    </td>

                                                    <td>
                                                        {{ $plot->booking_date
                                                            ? \Carbon\Carbon::parse($plot->booking_date)->format('d-m-Y')
                                                            : 'N/A' }}
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

        #customerListTable th,
        #customerListTable td {
            vertical-align: middle;
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