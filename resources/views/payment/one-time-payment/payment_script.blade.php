@push('scripts')
    <script>
        $(document).ready(function() {
            function formatAmount(amount) {
                return Number(amount || 0).toFixed(2);
            }

            function sanitizeAmount(value) {
                value = String(value || '').replace(/[^\d.]/g, '');
                const firstDot = value.indexOf('.');

                if (firstDot !== -1) {
                    value = value.substring(0, firstDot + 1) + value.substring(firstDot + 1).replace(/\./g, '');
                }

                return value;
            }

            function resetSummary() {
                $('#payment_type').val('');
                $('#booking_id').val('');
                $('#customer_id').val('');
                $('#customer_name').val('');
                $('#customer_booking_id').val('');
                $('#plot_sale_detail_id').val('');
                $('#total_cost').text('0.00');
                $('#total_paid').text('0.00');
                $('#hold_amount').text('0.00');
                $('#due_amount').text('0.00');
                $('#max_due_amount').val('0');
                $('#paid_amount').val('').removeAttr('max');
                $('#payment_history_count').text('0 Records');
                $('#fill_due_amount').addClass('d-none');

                $('#payment_history').html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">No payments found</td>
                    </tr>
                `);
            }

            function resetPaymentFields() {
                $('.bank-field').addClass('d-none');
                $('.cheque-field').addClass('d-none');
                $('.dd-field').addClass('d-none');
                $('.transaction-field').addClass('d-none');
            }

            function setButtonLoading(isLoading) {
                const button = $('#submitPaymentBtn');
                button.prop('disabled', isLoading);
                button.find('.btn-label').toggleClass('d-none', isLoading);
                button.find('.btn-loader').toggleClass('d-none', !isLoading);
            }

            function setSummaryLoading(isLoading) {
                $('#one_time_summary_loader').toggleClass('d-none', !isLoading);
                $('#project_id, #block_id, #plot_id, #paid_amount, #payment_mode, #submitPaymentBtn')
                    .prop('disabled', isLoading);
            }

            function updateFullDueButton() {
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;
                const hasBooking = Boolean($('#customer_booking_id').val() && $('#plot_sale_detail_id').val());
                $('#fill_due_amount').toggleClass('d-none', !(hasBooking && dueAmount > 0));
            }

            function validateAmount(showAlert = true) {
                const enteredAmount = parseFloat($('#paid_amount').val()) || 0;
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (enteredAmount <= 0) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid payment amount.'
                        });
                    }
                    return false;
                }

                if (dueAmount <= 0) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Due Amount',
                            text: 'This plot does not have any pending due amount.'
                        });
                    }
                    return false;
                }

                if (enteredAmount > dueAmount) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Amount cannot exceed due amount of Rs. ' + formatAmount(dueAmount) + '.'
                        });
                    }
                    $('#paid_amount').val(formatAmount(dueAmount));
                    return false;
                }

                return true;
            }

            $('#payment_mode').change(function() {
                resetPaymentFields();
                let mode = $(this).val();

                if (['cheque', 'dd', 'neft_rtgs'].includes(mode)) {
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

            $('#project_id').change(function() {
                resetSummary();
                let projectId = $(this).val();
                $('#block_id').html('<option value="">Select Block</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');

                if (!projectId) return;

                $.get("{{ route('one-time-payment.blocks', ':id') }}".replace(':id', projectId), function(res) {
                    $.each(res, function(index, block) {
                        $('#block_id').append(`<option value="${block.id}">${block.block}</option>`);
                    });
                });
            });

            $('#block_id').change(function() {
                resetSummary();
                let blockId = $(this).val();
                $('#plot_id').html('<option value="">Select Plot</option>');

                if (!blockId) return;

                $.get("{{ route('one-time-payment.plots', ':id') }}".replace(':id', blockId), function(res) {
                    $.each(res, function(index, plot) {
                        $('#plot_id').append(`<option value="${plot.id}">${plot.plot_number}</option>`);
                    });
                });
            });

            $('#plot_id').change(function() {
                resetSummary();
                let plotId = $(this).val();

                if (!plotId) return;

                setSummaryLoading(true);

                $.get("{{ route('one-time-payment.details', ':id') }}".replace(':id', plotId), function(res) {
                    setSummaryLoading(false);

                    if (!res.status) {
                        updateFullDueButton();
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Booking Found',
                            text: res.message || 'Booking details are not available for this plot.'
                        });
                        return;
                    }

                    $('#payment_type').val(res.payment_type);
                    $('#customer_booking_id').val(res.booking_db_id);
                    $('#plot_sale_detail_id').val(res.plot_sale_id);
                    $('#booking_id').val(res.booking_code);
                    $('#customer_id').val(res.customer_code);
                    $('#customer_name').val(res.customer_name);

                    $('#total_cost').text(res.total_cost);
                    $('#total_paid').text(res.total_paid);
                    $('#hold_amount').text(res.hold_amount || '0.00');
                    $('#due_amount').text(res.due_amount);
                    $('#max_due_amount').val(res.due_amount);
                    $('#paid_amount').attr('max', res.due_amount);
                    updateFullDueButton();

                    let historyHtml = '';
                    if (res.payment_history && res.payment_history.length > 0) {
                        $.each(res.payment_history, function(index, payment) {
                            const status = `${payment.booking_status ?? '-'} / ${payment.payment_status ?? '-'}`;
                            historyHtml += `<tr>
                                <td>${payment.receipt_no ?? '-'}</td>
                                <td>${payment.date ?? '-'}</td>
                                <td>&#8377;${payment.paid_amount ?? '0'}</td>
                                <td><span class="badge bg-light text-dark border">${status}</span></td>
                            </tr>`;
                        });
                        $('#payment_history_count').text(res.payment_history.length + ' Records');
                    } else {
                        historyHtml = `<tr>
                            <td colspan="4" class="text-center text-muted py-3">No payments found</td>
                        </tr>`;
                        $('#payment_history_count').text('0 Records');
                    }
                    $('#payment_history').html(historyHtml);
                }).fail(function() {
                    setSummaryLoading(false);
                    updateFullDueButton();
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: 'Unable to load booking details.'
                    });
                });
            });

            $('#paid_amount').on('input change blur', function() {
                const cleanedAmount = sanitizeAmount($(this).val());
                if ($(this).val() !== cleanedAmount) {
                    $(this).val(cleanedAmount);
                }

                const enteredAmount = parseFloat(cleanedAmount) || 0;
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (enteredAmount > dueAmount && dueAmount > 0) {
                    validateAmount(true);
                }
            });

            $('#fill_due_amount').on('click', function() {
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (dueAmount <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Due Amount',
                        text: 'This plot does not have any pending due amount.'
                    });
                    return;
                }

                $('#paid_amount').val(formatAmount(dueAmount)).focus();
            });

            $('#paymentForm').on('submit', function(event) {
                $('#paid_amount').val(sanitizeAmount($('#paid_amount').val()));

                if (!$('#customer_booking_id').val() || !$('#plot_sale_detail_id').val()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Plot',
                        text: 'Please select a valid booked plot first.'
                    });
                    return;
                }

                if (!validateAmount(true)) {
                    event.preventDefault();
                    return;
                }

                setButtonLoading(true);
            });

            $('#payment_mode').trigger('change');
        });
    </script>
@endpush
