@if ($step == 2)
    @php
        $primary = $customer?->primaryDetail;
        $secondaryToggle = old('fill_secondary_detail', $primary?->fill_secondary_detail ?? 'no');
    @endphp
    <form method="POST" action="{{ route('admin.customer-booking.update', $customer->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="step" value="2">
        @include('customer-booking.partials.primary-form')
        @include('customer-booking.partials.correspondence-form')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold border-bottom pb-2 mb-4">Secondary Applicant</h5>
                <label class="form-label fw-semibold d-block mb-3">Add Secondary Applicant?</label>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input secondaryToggle" name="fill_secondary_detail"
                        value="yes" {{ $secondaryToggle == 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input secondaryToggle" name="fill_secondary_detail"
                        value="no" {{ $secondaryToggle == 'no' ? 'checked' : '' }}>
                    <label class="form-check-label">No</label>
                </div>
                @error('fill_secondary_detail')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div id="secondarySection" style="{{ $secondaryToggle == 'yes' ? '' : 'display:none;' }}">
            @include('customer-booking.partials.secondary-form')
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.customer-booking.edit', [$customer->id, 'step' => 1]) }}"
                class="btn btn-outline-secondary px-4">Previous
            </a>
            <button type="submit" class="btn btn-success ms-2 px-4">Save & Next</button>
        </div>
    </form>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.addressToggle').on('change', function() {
                if ($(this).val() == 'yes') {
                    $('#corrAddress').val($('#primaryAddress').val());
                    $('#corrPin').val($('#primaryPin').val());
                    $('#corrCity').val($('#primaryCity').val());
                    $('#corrState').val($('#primaryState').val());
                } else {
                    $('#corrAddress').val('');
                    $('#corrPin').val('');
                    $('#corrCity').val('');
                    $('#corrState').val('');
                }
            });
            $('.secondaryToggle').on('change', function() {
                if ($(this).val() == 'yes') {
                    $('#secondarySection').stop(true, true).slideDown(300);
                } else {
                    $('#secondarySection').stop(true, true).slideUp(300);
                }

            });
            $(document).on('change', '.secondary_AddressToggle', function() {
                if ($(this).val() == 'yes') {
                    $('#secondary_CorrAddress').val($('#secondaryAddress').val());
                    $('#secondary_CorrPin').val($('#secondaryPin').val());
                    $('#secondary_CorrCity').val($('#secondaryCity').val());
                    $('#secondary_CorrState').val($('#secondaryState').val());
                } else {
                    $('#secondary_CorrAddress').val('');
                    $('#secondary_CorrPin').val('');
                    $('#secondary_CorrCity').val('');
                    $('#secondary_CorrState').val('');
                }
            });
        });
    </script>
@endpush
