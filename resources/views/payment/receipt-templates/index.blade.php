@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4 receipt-template-page">
        <div class="receipt-template-hero mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="receipt-template-hero-icon">
                        <i class="bi bi-file-earmark-richtext"></i>
                    </span>
                    <div>
                        <span class="text-success fw-bold text-uppercase small">Receipt Format</span>
                        <h3 class="fw-bold mb-1 text-dark">Receipt Templates</h3>
                        <p class="text-muted mb-0 small">Select the active PDF format used for admin and customer receipt downloads.</p>
                    </div>
                </div>

                <div class="receipt-template-active-box">
                    <small>Active Template</small>
                    <strong>{{ $activeTemplate?->name ?? 'Classic Receipt' }}</strong>
                </div>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="receipt-template-stat">
                    <small>Total Templates</small>
                    <strong>{{ $templates->count() }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="receipt-template-stat success">
                    <small>Active</small>
                    <strong>{{ $activeTemplate?->name ?? 'Default' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="receipt-template-stat info">
                    <small>Total Receipts</small>
                    <strong>{{ $receiptCount }}</strong>
                </div>
            </div>
        </div>

        <div class="receipt-template-grid">
            @forelse ($templates as $template)
                <div class="receipt-template-card {{ $template->is_active ? 'active' : '' }}">
                    <div class="receipt-template-preview {{ $template->slug }}">
                        <span>{{ $template->is_active ? 'Active' : 'Available' }}</span>
                        <div class="receipt-template-preview-paper">
                            <div class="receipt-template-paper-angle"></div>
                            <div class="receipt-template-paper-head">
                                <span></span>
                                <strong>SP REAL ESTATE</strong>
                            </div>
                            <p>RECEIPT</p>
                            <ul>
                                <li><span>Receipt No</span><b>RCP-0001</b></li>
                                <li><span>Customer</span><b>Amit Sharma</b></li>
                                <li><span>Plot</span><b>F / F-12</b></li>
                                <li><span>Amount</span><b>&#8377;80,000.00</b></li>
                            </ul>
                        </div>
                    </div>

                    <div class="receipt-template-body">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $template->name }}</h5>
                                <p class="text-muted mb-0 small">{{ $template->description ?? 'Receipt PDF format.' }}</p>
                            </div>

                            @if ($template->is_active)
                                <span class="receipt-template-badge active">
                                    <i class="bi bi-check2-circle"></i> Active
                                </span>
                            @else
                                <span class="receipt-template-badge">
                                    Ready
                                </span>
                            @endif
                        </div>

                        {{-- <div class="receipt-template-meta">
                            <span>View Path</span>
                            <strong>{{ $template->view_path }}</strong>
                        </div> --}}

                        <div class="receipt-template-actions mt-3">
                            <a href="{{ route('receipt-templates.preview', $template) }}" target="_blank"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i> Preview PDF
                            </a>

                            @can('receipt-templates-modify')
                                <form method="POST" action="{{ route('receipt-templates.activate', $template) }}"
                                    class="receipt-template-activate-form">
                                    @csrf
                                    <button type="submit"
                                        class="btn {{ $template->is_active ? 'btn-success' : 'btn-outline-success' }}"
                                        {{ $template->is_active ? 'disabled' : '' }}>
                                        @if ($template->is_active)
                                            <i class="bi bi-check2-circle me-1"></i> Active
                                        @else
                                            <i class="bi bi-lightning-charge me-1"></i> Set Active
                                        @endif
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="receipt-template-empty">
                    <i class="bi bi-inbox"></i>
                    <h5>No receipt templates found</h5>
                    <p class="mb-0">Run migration to create the default receipt templates.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
