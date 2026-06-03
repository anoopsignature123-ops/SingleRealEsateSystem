@push('scripts')
    <script>
        $(document).ready(function() {

            // Payment Mode Fields
            $('#payment_mode').on('change', function() {

                let mode = $(this).val();

                $('.bank-field, .cheque-field, .dd-field, .transaction-field')
                    .addClass('d-none');

                if (['cheque', 'dd', 'neft_rtgs', 'card'].includes(mode)) {
                    $('.bank-field').removeClass('d-none');
                }

                if (mode === 'cheque') {
                    $('.cheque-field').removeClass('d-none');
                }

                if (mode === 'dd') {
                    $('.dd-field').removeClass('d-none');
                }

                if (['neft_rtgs', 'card'].includes(mode)) {
                    $('.transaction-field').removeClass('d-none');
                }
            });

            $('#payment_mode').trigger('change');

            // Project -> Block
            $('#project_id').on('change', function() {

                let projectId = $(this).val();

                $('#block_id').html('<option value="">Select Block</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');

                clearBookingData();

                if (!projectId) return;

                $.get('/emi-payment/blocks/' + projectId, function(res) {

                    $.each(res.data, function(index, block) {

                        $('#block_id').append(`
                    <option value="${block.id}">
                        ${block.block}
                    </option>
                `);

                    });
                });
            });

            // Block -> Plot
            $('#block_id').on('change', function() {

                let blockId = $(this).val();

                $('#plot_id').html('<option value="">Select Plot</option>');

                clearBookingData();

                if (!blockId) return;

                $.get('/emi-payment/plots/' + blockId, function(res) {

                    if (!res.status) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Alert',
                            text: res.message,
                            confirmButtonColor: '#198754'
                        });

                        return;
                    }

                    $.each(res.data, function(index, plot) {

                        $('#plot_id').append(`
                    <option value="${plot.id}">
                        ${plot.plot_number}
                    </option>
                `);

                    });

                });

            });

            // Plot -> Booking Details
            $('#plot_id').on('change', function() {

                let plotId = $(this).val();

                clearBookingData();

                if (!plotId) return;

                $.get('/emi-payment/details/' + plotId, function(res) {

                    if (!res.status) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Not Found',
                            text: 'EMI booking details not found.'
                        });

                        return;
                    }

                    // Hidden Fields
                    $('#customer_booking_id').val(res.booking_db_id);
                    $('#plot_sale_detail_id').val(res.plot_sale_id);

                    // Customer Info
                    $('#booking_id').val(res.booking_code);
                    $('#customer_id').val(res.customer_code);
                    $('#customer_name').val(res.customer_name);

                    // Summary
                    $('#total_cost').html('₹' + res.total_cost);
                    $('#booking_amount').html('₹' + res.booking_amount);
                    $('#total_paid').html('₹' + res.total_paid);
                    $('#due_amount').html('₹' + res.due_amount);

                    $('#emi_start_date').html(res.emi_start_date);

                    $('#emi_months').html(
                        res.months_passed + ' / ' + res.emi_months + ' Months'
                    );

                    $('#monthly_emi').html('₹' + res.monthly_emi);

                    // EMI Input
                    $('#booking_amount_input').val(res.monthly_emi);
                    $('#monthly_emi_value').val(res.monthly_emi);
                    $('#minimum_emi').text('₹' + res.monthly_emi);

                    // Payment History
                    let html = '';

                    if (res.payment_history.length > 0) {

                        $.each(res.payment_history, function(index, payment) {

                            html += `
                        <tr>
                            <td>${payment.receipt_no}</td>
                            <td>${payment.date}</td>
                            <td>₹${payment.amount}</td>
                            <td>${payment.mode}</td>
                        </tr>
                    `;
                        });

                    } else {

                        html = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No Payment Found
                        </td>
                    </tr>
                `;
                    }

                    $('#payment_history').html(html);

                });

            });

            // EMI Amount Validation
            $('form').on('submit', function(e) {

                let minEmi = parseFloat(
                    $('#monthly_emi_value').val()
                ) || 0;

                let enteredAmount = parseFloat(
                    $('#booking_amount_input').val()
                ) || 0;

                let dueAmount = parseFloat(
                    $('#due_amount')
                    .text()
                    .replace('₹', '')
                    .replace(/,/g, '')
                ) || 0;

                if (
                    enteredAmount < minEmi &&
                    enteredAmount !== dueAmount
                ) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid EMI Amount',
                        text: 'Minimum EMI Amount is ₹' + minEmi
                    });

                    return false;
                }

            });

        });

        // Reset Data
        function clearBookingData() {

            $('#customer_booking_id').val('');
            $('#plot_sale_detail_id').val('');

            $('#booking_id').val('');
            $('#customer_id').val('');
            $('#customer_name').val('');

            $('#total_cost').html('₹0.00');
            $('#booking_amount').html('₹0.00');
            $('#total_paid').html('₹0.00');
            $('#due_amount').html('₹0.00');

            $('#emi_start_date').html('-');
            $('#emi_months').html('0 / 0 Months');
            $('#monthly_emi').html('₹0.00');

            $('#booking_amount_input').val('');
            $('#monthly_emi_value').val('');
            $('#minimum_emi').text('₹0');

            $('#payment_history').html(`
        <tr>
            <td colspan="4" class="text-center text-muted">
                No Payment Found
            </td>
        </tr>
    `);
        }
    </script>
@endpush
