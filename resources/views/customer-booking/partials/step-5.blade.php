@if ($step == 5)
    <form method="POST" action="{{ route('admin.customer-booking.update', $customer->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="step" value="5">
        @include('customer-booking.partials.payment-form')
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.customer-booking.edit', [$customer->id, 'step' => 4]) }}"
                class="btn btn-outline-secondary px-4">Previous
            </a>
            <button type="submit" class="btn btn-success ms-2 px-4">Add Customer & Book Plot</button>
        </div>
    </form>
@endif
@push('scripts')
    <script>
        $(document).ready(function() {
            function resetFields() {
                $('.common-field').addClass('d-none');
                $('.full-field').addClass('d-none');
                $('.emi-field').addClass('d-none');
                $('.bank-field').addClass('d-none');
                $('.instrument-field').addClass('d-none');
                $('.bank-detail-field').addClass('d-none');
            }

            function calculateAmounts() {
                let booking = parseFloat($('#bookingAmount').val()) || 0;
                let totalPlot = parseFloat($('#totalPlotCost').val()) || 0;
                let due = totalPlot - booking;
                if (due < 0) {
                    due = 0;
                }
                $('#dueAmount').val(due.toFixed(2));
                let plan = $('#planType').val();
                if (plan == 'full_payment') {
                    $('#netPayable').val(due.toFixed(2));
                }
                if (plan == 'emi_plan') {
                    let emiAmount = due / 12;
                    $('#afterBookingAmount').val(emiAmount.toFixed(2));
                }
            }

            function loadPaymentFields() {
                resetFields();
                let plan = $('#planType').val();
                if (plan) {
                    $('.common-field').removeClass('d-none');
                }
                if (plan == 'full_payment') {
                    $('.full-field').removeClass('d-none');
                }
                if (plan == 'emi_plan') {
                    $('.emi-field').removeClass('d-none');
                }
                $('#paymentMode').trigger('change');
                calculateAmounts();
            }
            $('#planType').change(function() {
                loadPaymentFields();
            });
            $('#paymentMode').change(function() {
                let mode = $(this).val();
                $('.bank-field').addClass('d-none');
                $('.instrument-field').addClass('d-none');
                $('.bank-detail-field').addClass('d-none');
                if (mode == 'cheque' || mode == 'dd' || mode == 'neft_rtgs') {
                    $('.bank-field').removeClass('d-none');
                    $('.instrument-field').removeClass('d-none');
                    $('.bank-detail-field').removeClass('d-none');
                }
                if (mode == 'card') {
                    $('.bank-field').removeClass('d-none');
                }
            });
            $('#bookingAmount').on('keyup change', function() {
                calculateAmounts();
            });
            loadPaymentFields();
        });
    </script>
@endpush
