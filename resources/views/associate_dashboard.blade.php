{{-- @extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush
@section('content')
    <div class="container-fluid px-4 py-4" style="background-color: #f4f6f9; min-height: 100vh;">
        <div class="row mb-4 mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <span class="badge badge-theme-subtle mb-2 px-2.5 py-1 text-uppercase fw-semibold"
                                style="letter-spacing: 0.5px; font-size: 0.75rem;">Associate Workspace</span>
                            <h3 class="mb-1 text-dark fw-bold" style="letter-spacing: -0.5px;">Welcome back, Realestate</h3>
                            <p class="text-muted mb-0 small">Overview your property sales matrix, team performance, and
                                statements.</p>
                        </div>
                        <button class="btn btn-theme-outline btn-sm rounded-2 px-3 fw-medium">
                            <i class="bi bi-person-gear me-1"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1"
                                style="font-size: 0.75rem;">My Direct</p>
                            <h2 class="display-6 fw-bold text-dark mb-0">5</h2>
                        </div>
                        <div class="rounded-3 p-3 badge-theme-subtle">
                            <i class="bi bi-person-plus-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1"
                                style="font-size: 0.75rem;">My Team</p>
                            <h2 class="display-6 fw-bold text-dark mb-0">5</h2>
                        </div>
                        <div class="rounded-3 p-3 badge-theme-subtle">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1"
                                style="font-size: 0.75rem;">Self Business</p>
                            <h2 class="fs-3 fw-bold text-dark mb-0">₹64,99,002.00</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-success-subtle text-success">
                            <i class="bi bi-currency-rupee fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="bi bi-pie-chart-fill text-theme-green me-2"></i>Business Breakdown Summary</h5>
                        <span class="text-muted text-xs font-monospace">Confirmed vs Pending</span>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center mb-3 mb-md-0">
                                <div style="max-height: 200px; position: relative;">
                                    <canvas id="businessDonutChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="p-3 bg-light rounded-3 border-0">
                                    <h6 class="text-dark small fw-bold mb-3 text-uppercase font-monospace"
                                        style="letter-spacing: 0.5px;">This Month Metrics</h6>
                                    <div class="d-flex justify-content-between small border-bottom pb-2 mb-2">
                                        <span class="text-muted"><i
                                                class="bi bi-circle-fill text-theme-green me-2 small"></i>Confirmed
                                            Sales:</span>
                                        <span class="fw-bold text-dark">₹8,45,23,35.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between small border-bottom pb-2 mb-2">
                                        <span class="text-muted"><i
                                                class="bi bi-circle-fill text-secondary opacity-50 me-2 small"></i>Pending
                                            Approvals:</span>
                                        <span class="fw-bold text-dark">₹5,61,89,665.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between small pt-1">
                                        <span class="text-dark fw-bold">Grand Total Net:</span>
                                        <span class="fw-bold text-theme-green">₹64,642,000.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="bi bi-person-badge-fill text-theme-green me-2"></i>Profile Verification</h5>
                    </div>
                    <div class="card-body p-0 pt-2">
                        <ul class="list-group list-group-flush mb-0 text-sm">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Joining Date</span>
                                <span class="text-dark fw-medium">01-Apr-2021</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Registration Type</span>
                                <span class="text-dark fw-medium">Direct Entry</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Sponsor Name</span>
                                <span class="text-muted font-monospace">-</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Current Rank</span>
                                <span class="badge bg-theme-green px-3 py-1.5 rounded-pill font-monospace">DS Rank</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white rounded-3">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark"><i
                                    class="bi bi-receipt-cutoff text-theme-green me-2"></i>Recent Financial Ledger</h5>
                            <p class="text-muted mb-0 small">Real-time breakdown of plots booked under your direct/downline
                                chain.</p>
                        </div>
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-2 fw-medium">13 Active
                            Records</span>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover mb-0 text-sm align-middle">
                            <thead class="bg-light text-muted text-uppercase font-monospace"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-4 py-3">Sr</th>
                                    <th class="py-3">Pay Date</th>
                                    <th class="py-3">Plot Designation</th>
                                    <th class="py-3">Transaction Method</th>
                                    <th class="py-3">Payment Group</th>
                                    <th class="py-3 text-end">Payable</th>
                                    <th class="pe-4 py-3 text-end">Paid Amount</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">01</td>
                                    <td class="text-dark">07-05-2025</td>
                                    <td><span class="badge badge-plot px-2.5 py-1">B-12</span></td>
                                    <td class="font-monospace text-muted text-xs">NEFT/RTGS / 45855352536553</td>
                                    <td class="text-secondary">Full Payment</td>
                                    <td class="text-end fw-medium">₹6,79,000.00</td>
                                    <td class="pe-4 text-end fw-bold text-theme-green">₹5,00,000.00</td>
                                </tr>
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">02</td>
                                    <td class="text-dark">16-04-2025</td>
                                    <td><span class="badge badge-plot px-2.5 py-1">C-C-312</span></td>
                                    <td class="font-monospace text-muted text-xs">Cheque / 2435465677</td>
                                    <td class="text-secondary">Installment Mode</td>
                                    <td class="text-end fw-medium">₹1,05,00,000.00</td>
                                    <td class="pe-4 text-end fw-bold text-theme-green">₹8,66,667.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('businessDonutChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmed Business', 'Pending Business'],
                    datasets: [{
                        data: [8452335, 56189665],
                        backgroundColor: ['#0f8a53',
                            '#e2e8f0'
                        ],
                        borderWidth: 0,
                        hoverOffset: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '78%'
                }
            });
        });
    </script>
@endpush --}}
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid px-4 py-4" style="background-color: #f4f6f9; min-height: 100vh;">

        {{-- Header Section --}}
        <div class="row mb-4 mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <span class="badge badge-theme-subtle mb-2 px-2.5 py-1 text-uppercase fw-semibold"
                                style="letter-spacing: 0.5px; font-size: 0.75rem;">Associate Workspace</span>
                            <h3 class="mb-1 text-dark fw-bold" style="letter-spacing: -0.5px;">Welcome back,
                                {{ $associate->associate_name }}</h3>
                            <p class="text-muted mb-0 small">Overview your property sales matrix, team performance, and
                                statements.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Row --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1">My Direct</p>
                            <h2 class="display-6 fw-bold text-dark mb-0">{{ $data['direct_count'] }}</h2>
                        </div>
                        <div class="rounded-3 p-3 badge-theme-subtle"><i class="bi bi-person-plus-fill fs-3"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1">My Team</p>
                            <h2 class="display-6 fw-bold text-dark mb-0">{{ $data['team_count'] }}</h2>
                        </div>
                        <div class="rounded-3 p-3 badge-theme-subtle"><i class="bi bi-people-fill fs-3"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted small fw-bold tracking-wider mb-1">Self Business</p>
                            <h2 class="fs-3 fw-bold text-dark mb-0">₹{{ number_format($data['total_business'], 2) }}</h2>
                        </div>
                        <div class="rounded-3 p-3 bg-success-subtle text-success"><i class="bi bi-currency-rupee fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart and Verification Row --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="bi bi-pie-chart-fill text-theme-green me-2"></i>Business Breakdown Summary</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 200px;">
                            <canvas id="businessDonutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm bg-white rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="bi bi-person-badge-fill text-theme-green me-2"></i>Profile Verification</h5>
                    </div>
                    <div class="card-body p-0 pt-2">
                        <ul class="list-group list-group-flush mb-0 text-sm">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Joining Date</span>
                                <span class="text-dark fw-medium">{{ $associate->created_at->format('d-M-Y') }}</span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-2.5 bg-transparent">
                                <span class="text-muted">Sponsor Name</span>
                                <span
                                    class="text-dark fw-medium">{{ $associate->sponsor->associate_name ?? 'Self/Direct' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Financial Ledger Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-white rounded-3">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-receipt-cutoff text-theme-green me-2"></i>Recent
                            Financial Ledger</h5>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover mb-0 text-sm align-middle">
                            <thead class="bg-light text-muted text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Date</th>
                                    <th class="py-3">Plot Detail</th>
                                    <th class="py-3">Mode</th>
                                    <th class="py-3 text-end">Paid Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_ledgers'] as $ledger)
                                    <tr>
                                        <td class="ps-4 text-dark">{{ $ledger->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $ledger->customerBooking->plotSaleDetail->plotDetail->plot_number ?? 'N/A' }}
                                        </td>
                                        <td>{{ $ledger->payment_mode }}</td>
                                        <td class="pe-4 text-end fw-bold text-theme-green">
                                            ₹{{ number_format($ledger->paid_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center p-4">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('businessDonutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmed', 'Pending'],
                    datasets: [{
                        data: [{{ $data['confirmed_sales'] }}, {{ $data['pending_sales'] }}],
                        backgroundColor: ['#0f8a53', '#e2e8f0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '78%'
                }
            });
        });
    </script>
@endpush
