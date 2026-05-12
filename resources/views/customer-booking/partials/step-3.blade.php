@if ($step == 3)
    @php
        $primary = $customer->primaryDetail;
    @endphp
    <form method="POST" action="{{ route('admin.customer-booking.update', $customer->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="step" value="3">
        @include('customer-booking.partials.document-form', [
            'prefix' => '',
            'title' => 'Primary Applicant Documents',
            'document' => $customer->primaryDocument ?? null,
        ])
        @if ($primary?->fill_secondary_detail == 'yes')
            @include('customer-booking.partials.document-form', [
                'prefix' => 'secondary_',
                'title' => 'Secondary Applicant Documents',
                'document' => $customer->secondaryDocument ?? null,
            ])
        @endif
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.customer-booking.edit', [$customer->id, 'step' => 2]) }}"
                class="btn btn-outline-secondary px-4">
                Previous
            </a>
            <button type="submit" class="btn btn-success ms-2 px-4">Save & Next</button>
        </div>
    </form>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.doc-check').each(function() {
                let target = $(this).data('target');
                if ($(this).is(':checked')) {
                    $('#' + target).show();
                } else {
                    $('#' + target).hide();
                }
            });
            $(document).on('change', '.doc-check', function() {
                let target = $(this).data('target');
                if ($(this).is(':checked')) {
                    $('#' + target).stop(true, true).slideDown(300);
                } else {
                    $('#' + target).stop(true, true).slideUp(300);
                    $('#' + target).find('input[type="file"]').val('');
                }
            });
        });
    </script>
@endpush
