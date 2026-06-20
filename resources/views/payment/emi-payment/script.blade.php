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

            function setSummaryLoading(isLoading) {
                $('#emi_summary_loader').toggleClass('d-none', !isLoading);
                $('#project_id, #block_id, #plot_id, #booking_amount_input, #payment_mode, #submitEmiPaymentBtn')
                    .prop('disabled', isLoading);
            }

            function setButtonLoading(isLoading) {
                const button = $('#submitEmiPaymentBtn');
                button.prop('disabled', isLoading);
                button.find('.btn-label').toggleClass('d-none', isLoading);
                button.find('.btn-loader').toggleClass('d-none', !isLoading);
            }

            function resetPaymentFields() {
                $('.bank-field, .cheque-field, .dd-field, .transaction-field').addClass('d-none');
            }

            function updateQuickButtons() {
                const hasBooking = Boolean($('#customer_booking_id').val() && $('#plot_sale_detail_id').val());
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;
                const monthlyEmi = parseFloat($('#monthly_emi_value').val()) || 0;

                $('#fill_monthly_emi').toggleClass('d-none', !(hasBooking && monthlyEmi > 0 && dueAmount > 0));
                $('#fill_due_amount').toggleClass('d-none', !(hasBooking && dueAmount > 0));
            }

            function clearBookingData() {
                $('#customer_booking_id').val('');
                $('#plot_sale_detail_id').val('');
                $('#booking_id').val('');
                $('#customer_id').val('');
                $('#customer_name').val('');

                $('#total_cost').text('0.00');
                $('#booking_amount').text('0.00');
                $('#total_paid').text('0.00');
                $('#hold_amount').text('0.00');
                $('#due_amount').text('0.00');
                $('#emi_start_date').text('-');
                $('#emi_months').text('0 / 0 Months');
                $('#monthly_emi').text('0.00');

                $('#booking_amount_input').val('').removeAttr('max');
                $('#monthly_emi_value').val('');
                $('#max_due_amount').val('0');
                $('#minimum_emi').html('&#8377;0.00');
                $('#payment_history_count').text('0 Records');

                $('#payment_history').html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">No Payment Found</td>
                    </tr>
                `);

                updateQuickButtons();
            }

            function validateAmount(showAlert = true) {
                const enteredAmount = parseFloat($('#booking_amount_input').val()) || 0;
                const monthlyEmi = parseFloat($('#monthly_emi_value').val()) || 0;
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (enteredAmount <= 0) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid EMI amount.'
                        });
                    }
                    return false;
                }

                if (dueAmount <= 0) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Due Amount',
                            text: 'This booking does not have any pending EMI due.'
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
                    $('#booking_amount_input').val(formatAmount(dueAmount));
                    return false;
                }

                if (enteredAmount < monthlyEmi && enteredAmount < dueAmount) {
                    if (showAlert) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid EMI Amount',
                            text: 'Minimum EMI amount is Rs. ' + formatAmount(monthlyEmi) + '.'
                        });
                    }
                    return false;
                }

                return true;
            }

            $('#payment_mode').on('change', function() {
                resetPaymentFields();
                const mode = $(this).val();

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

            $('#project_id').on('change', function() {
                const projectId = $(this).val();
                $('#block_id').html('<option value="">Select Block</option>');
                $('#plot_id').html('<option value="">Select Plot</option>');
                clearBookingData();

                if (!projectId) return;

                $.get("{{ route('emi-payment.blocks', ':id') }}".replace(':id', projectId), function(res) {
                    if (!res.status) return;

                    $.each(res.data, function(index, block) {
                        $('#block_id').append(`<option value="${block.id}">${block.block}</option>`);
                    });
                });
            });

            $('#block_id').on('change', function() {
                const blockId = $(this).val();
                $('#plot_id').html('<option value="">Select Plot</option>');
                clearBookingData();

                if (!blockId) return;

                $.get("{{ route('emi-payment.plots', ':id') }}".replace(':id', blockId), function(res) {
                    if (!res.status) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Pending EMI',
                            text: res.message,
                            confirmButtonColor: '#198754'
                        });
                        return;
                    }

                    $.each(res.data, function(index, plot) {
                        $('#plot_id').append(`<option value="${plot.id}">${plot.plot_number}</option>`);
                    });
                });
            });

            $('#plot_id').on('change', function() {
                const plotId = $(this).val();
                clearBookingData();

                if (!plotId) return;

                setSummaryLoading(true);

                $.get("{{ route('emi-payment.details', ':id') }}".replace(':id', plotId), function(res) {
                    setSummaryLoading(false);

                    if (!res.status) {
                        updateQuickButtons();
                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Not Found',
                            text: res.message || 'EMI booking details not found.'
                        });
                        return;
                    }

                    $('#customer_booking_id').val(res.booking_db_id);
                    $('#plot_sale_detail_id').val(res.plot_sale_id);
                    $('#booking_id').val(res.booking_code);
                    $('#customer_id').val(res.customer_code);
                    $('#customer_name').val(res.customer_name);

                    $('#total_cost').text(res.total_cost);
                    $('#booking_amount').text(res.booking_amount);
                    $('#total_paid').text(res.total_paid);
                    $('#hold_amount').text(res.hold_amount || '0.00');
                    $('#due_amount').text(res.due_amount);
                    $('#emi_start_date').text(res.emi_start_date);
                    $('#emi_months').text(res.months_passed + ' / ' + res.emi_months + ' Months');
                    $('#monthly_emi').text(res.monthly_emi);

                    $('#booking_amount_input').val(res.monthly_emi).attr('max', res.due_amount);
                    $('#monthly_emi_value').val(res.monthly_emi);
                    $('#max_due_amount').val(res.due_amount);
                    $('#minimum_emi').html('&#8377;' + res.monthly_emi);

                    let html = '';
                    if (res.payment_history && res.payment_history.length > 0) {
                        $.each(res.payment_history, function(index, payment) {
                            html += `<tr>
                                <td>${payment.receipt_no ?? '-'}</td>
                                <td>${payment.date ?? '-'}</td>
                                <td>&#8377;${payment.amount ?? '0'}</td>
                                <td><span class="badge bg-light text-dark border">${payment.status ?? '-'}</span></td>
                            </tr>`;
                        });
                        $('#payment_history_count').text(res.payment_history.length + ' Records');
                    } else {
                        html = `<tr>
                            <td colspan="4" class="text-center text-muted py-3">No Payment Found</td>
                        </tr>`;
                        $('#payment_history_count').text('0 Records');
                    }

                    $('#payment_history').html(html);
                    updateQuickButtons();
                }).fail(function() {
                    setSummaryLoading(false);
                    updateQuickButtons();
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: 'Unable to load EMI details.'
                    });
                });
            });

            $('#booking_amount_input').on('input change blur', function() {
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

            $('#fill_monthly_emi').on('click', function() {
                const monthlyEmi = parseFloat($('#monthly_emi_value').val()) || 0;
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (monthlyEmi <= 0 || dueAmount <= 0) return;

                $('#booking_amount_input').val(formatAmount(Math.min(monthlyEmi, dueAmount))).focus();
            });

            $('#fill_due_amount').on('click', function() {
                const dueAmount = parseFloat($('#max_due_amount').val()) || 0;

                if (dueAmount <= 0) return;

                $('#booking_amount_input').val(formatAmount(dueAmount)).focus();
            });

            $('#emiPaymentForm').on('submit', function(event) {
                $('#booking_amount_input').val(sanitizeAmount($('#booking_amount_input').val()));

                if (!$('#customer_booking_id').val() || !$('#plot_sale_detail_id').val()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Plot',
                        text: 'Please select a valid EMI plot first.'
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
