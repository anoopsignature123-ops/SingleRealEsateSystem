@php
    $totalSteps = 5;
    $progress = ($step / $totalSteps) * 100;

    $steps = [
        1 => ['title' => 'Basic Details', 'icon' => 'bi-person'],
        2 => ['title' => 'Applicant', 'icon' => 'bi-file-earmark-text'],
        3 => ['title' => 'Documents', 'icon' => 'bi-folder2-open'],
        4 => ['title' => 'Plot Details', 'icon' => 'bi-grid'],
        5 => ['title' => 'Payment', 'icon' => 'bi-credit-card'],
    ];
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Customer Booking Process</h5>
                <small class="text-muted">Complete all steps to finish booking</small>
            </div>
            <span class="badge bg-success px-3 py-2 rounded-pill">Step {{ $step }} / {{ $totalSteps }}</span>
        </div>
        <div class="progress mb-4 rounded-pill" style="height:10px;">
            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
        </div>
        <div class="row g-2">
            @foreach ($steps as $number => $item)
                @php
                    $isActive = $step == $number;
                    $isCompleted = $step > $number;
                    $canClick = isset($customer);
                @endphp
                <div class="col">
                    <a href="{{ $canClick ? route('admin.customer-booking.edit', [$customer->id, 'step' => $number]) : 'javascript:void(0)' }}"
                        class="text-decoration-none booking-step" data-can-click="{{ $canClick ? 'yes' : 'no' }}">
                        <div
                            class="border rounded-4 p-3 text-center
                            {{ $isActive ? 'border-success bg-success text-white shadow-sm' : '' }}
                            {{ $isCompleted ? 'border-success bg-light' : '' }}
                            {{ !$isActive && !$isCompleted ? 'bg-white border-light' : '' }}">
                            <i class="bi {{ $item['icon'] }} fs-5 d-block mb-2"></i>
                            <small class="fw-bold d-block">STEP {{ $number }}</small>
                            <small>{{ $item['title'] }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.booking-step').click(function(e) {
                let canClick = $(this).data('can-click');
                if (canClick === 'no') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Complete Current Step',
                        text: 'Please save your current details before moving to the next step.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endpush
