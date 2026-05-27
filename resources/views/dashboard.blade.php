@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- Dashboard Header --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; border: 2px solid #e9ecef;">
                            <span class="fs-4 fw-bold text-primary">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Welcome back, {{ Auth::user()->name ?? 'Admin' }}</h5>
                            <p class="text-muted small mb-0">You have a productive day ahead. Here is the overview.</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem;">System Time
                        </div>
                        <div class="fw-bold text-dark">{{ now()->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    [
                        'title' => 'Total Projects',
                        'value' => $projectCount,
                        'icon' => 'bi-buildings-fill',
                        'color' => 'primary',
                        'route' => route('projects.index'),
                    ],
                    [
                        'title' => 'Total Plots',
                        'value' => $totalPlot,
                        'icon' => 'bi-grid-1x2-fill',
                        'color' => 'success',
                        'route' => route('plot-details.index'),
                    ],
                    [
                        'title' => 'Total Customers',
                        'value' => $totalCustomer,
                        'icon' => 'bi-people-fill',
                        'color' => 'info',
                        'route' => route('customer-booking.index'),
                    ],
                    [
                        'title' => 'Total Associates',
                        'value' => $totalAssociate,
                        'icon' => 'bi-person-badge-fill',
                        'color' => 'warning',
                        'route' => route('associate.index'),
                    ],
                    [
                        'title' => 'Booked Plot',
                        'value' => $totalBookedPlot,
                        'icon' => 'bi-house-check-fill',
                        'color' => 'danger',
                        'route' => route('plot-availability.index'),
                    ],
                    [
                        'title' => 'Hold Plot',
                        'value' => $totalHoldPlot,
                        'icon' => 'bi-pause-circle-fill',
                        'color' => 'secondary',
                        'route' => route('plot-availability.index'),
                    ],
                    [
                        'title' => 'Registry Plot',
                        'value' => $totalRegistryPlot,
                        'icon' => 'bi-journal-check',
                        'color' => 'dark',
                        'route' => route('plot-availability.index'),
                    ],
                    [
                        'title' => 'Available Plot',
                        'value' => $totalAvailablePlot,
                        'icon' => 'bi-check-circle-fill',
                        'color' => 'success',
                        'route' => route('plot-availability.index'),
                    ],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="col-xxl-3 col-xl-4 col-md-6">
                    <a href="{{ $card['route'] }}" class="text-decoration-none">
                        <div class="card border-0 rounded-4 overflow-hidden h-100 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <small class="text-muted fw-semibold text-uppercase">
                                            {{ $card['title'] }}
                                        </small>
                                        <h1 class="fw-bold text-dark mt-2 mb-0" style="font-size: 2.2rem;">
                                            {{ $card['value'] }}
                                        </h1>
                                    </div>
                                    <div class="rounded-4 bg-{{ $card['color'] }} bg-opacity-10 text-{{ $card['color'] }} d-flex align-items-center justify-content-center"
                                        style="width: 70px; height: 70px;">
                                        <i class="bi {{ $card['icon'] }} fs-2"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span
                                        class="badge rounded-pill bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }} px-3 py-2">
                                        Live Data
                                    </span>
                                    <div class="text-muted">
                                        <i class="bi bi-arrow-right-circle fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{-- Analytics Section --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1">Business Analytics</h5>
                                <small class="text-muted">Monthly visitors & booking performance</small>
                            </div>
                            <select class="form-select w-auto rounded-pill">
                                <option>This Month</option>
                                <option>Last Month</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div style="height: 350px;"><canvas id="mainChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-1">Sales Conversion</h5>
                        <small class="text-muted">Customer engagement ratio</small>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;"><canvas id="pieChart"></canvas></div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-semibold">Booked Plot</span>
                                <span class="fw-bold text-success">46%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-semibold">Pending Leads</span>
                                <span class="fw-bold text-warning">30%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">Others</span>
                                <span class="fw-bold text-danger">24%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section: Transactions & Actions --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1">Recent Transactions</h5>
                                <small class="text-muted">Latest payment and booking records</small>
                            </div>
                            <button class="btn btn-success rounded-pill px-4">View All</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Booking ID</th>
                                    <th>Customer</th>
                                    <th>Project</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 fw-semibold">#BK101</td>
                                    <td>Rahul Sharma</td>
                                    <td>Green City</td>
                                    <td class="fw-bold text-success">₹25,000</td>
                                    <td><span
                                            class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Completed</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 fw-semibold">#BK102</td>
                                    <td>Amit Singh</td>
                                    <td>Royal Palm</td>
                                    <td class="fw-bold text-warning">₹18,500</td>
                                    <td><span
                                            class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Pending</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 fw-semibold">#BK103</td>
                                    <td>Vikash Kumar</td>
                                    <td>Palm Valley</td>
                                    <td class="fw-bold text-success">₹32,000</td>
                                    <td><span
                                            class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="fw-bold mb-0">Performance</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2"><span class="fw-semibold">Sales
                                    Target</span><span>78%</span></div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width:78%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2"><span
                                    class="fw-semibold">Collection</span><span>65%</span></div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width:65%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-2"><span class="fw-semibold">Customer
                                    Growth</span><span>90%</span></div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width:90%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Main Analytics Chart
        new Chart(document.getElementById('mainChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Visitors',
                    data: [1200, 1900, 3000, 5000, 2500, 4000],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#198754'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Doughnut Chart
        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: ['Booked', 'Pending', 'Others'],
                datasets: [{
                    data: [46, 30, 24],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endpush
