@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Customer Booking</h3>
                <small class="text-muted">Add new customer booking details</small>
            </div>
            <a href="{{ route('admin.customer-booking.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                @include('customer-booking.partials.stepper', ['step' => $step])
                <form method="POST" enctype="multipart/form-data"
                    action="{{ isset($customer)
                        ? route('admin.customer-booking.update', $customer->id)
                        : route('admin.customer-booking.store') }}">
                    @csrf
                    @if (isset($customer))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="step" value="{{ $step }}">
                    @if ($step == 1)
                        @include('customer-booking.partials.step-1')
                    @endif
                    @if ($step == 2)
                        @include('customer-booking.partials.step-2')
                    @endif
                    @if ($step == 3)
                        @include('customer-booking.partials.step-3')
                    @endif
                    @if ($step == 4)
                        @include('customer-booking.partials.step-4')
                    @endif
                    @if ($step == 5)
                        @include('customer-booking.partials.step-5')
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
