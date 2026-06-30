@push('scripts')
<script>
let sourcePaymentTransfer = {
    customerBookingId: null,
    plotSaleIds: []
};

$(document).ready(function () {

    $('#projectId').on('change', function () {
        let projectId = $(this).val();

        resetAll();

        $('#blockId').html('<option value="">Loading...</option>');
        $('#plotId').html('<option value="">Select Plot</option>');

        if (!projectId) {
            $('#blockId').html('<option value="">Select Block</option>');
            return;
        }

        $.get(`/payment-transfer/blocks/${projectId}`, function (res) {
            let options = '<option value="">Select Block</option>';

            $.each(res, function (index, block) {
                options += `<option value="${block.id}">${block.block}</option>`;
            });

            $('#blockId').html(options);
        });
    });

    $('#blockId').on('change', function () {
        let blockId = $(this).val();

        resetAll(false);

        $('#plotId').html('<option value="">Loading...</option>');

        if (!blockId) {
            $('#plotId').html('<option value="">Select Plot</option>');
            return;
        }

        $.get(`/payment-transfer/plots/${blockId}`, function (res) {
            let options = '<option value="">Select Plot</option>';

            if (res.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Plot Found',
                    text: 'No plot payments were found in this block for payment transfer.'
                });
            }

            $.each(res, function (index, plot) {
                options += `<option value="${plot.id}">${plot.plot_number}</option>`;
            });

            $('#plotId').html(options);
        });
    });

    $('#plotId').on('change', function () {
        let plotId = $(this).val();

        clearPaymentSection();

        if (!plotId) return;

        $.get(`/payment-transfer/payments/${plotId}`, function (res) {

            if (!res.status) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Payment Found',
                    text: res.message || 'Selected plot payment not found.'
                });
                return;
            }

            $('#sourceBookingCode').val(res.booking_code);
            $('#sourceCustomerCode').val(res.customer_code);
            $('#sourceCustomerName').val(res.customer_name);
            $('#sourceProject').val(res.project_name);
            $('#sourceBlock').val(res.block_name);
            $('#sourcePlot').val(`${res.plot_number}${res.plot_count > 1 ? ' (' + res.plot_count + ' Plots)' : ''}`);
            sourcePaymentTransfer.customerBookingId = String(res.customer_booking_id || '');
            sourcePaymentTransfer.plotSaleIds = (res.plot_sale_ids || [res.plot_sale_id])
                .map(function (id) {
                    return String(id);
                });

            $('#sourceDetailsCard').removeClass('d-none');
            $('#paymentListCard').removeClass('d-none');
            $('#transferCard').removeClass('d-none');

            renderPayments(res.payments);
            loadCustomers();
        });
    });

    $('#selectAllPayments').on('change', function () {
        $('.payment-checkbox').prop('checked', $(this).is(':checked'));
        updatePaymentSelectionSummary();
    });

    $(document).on('change', '.payment-checkbox', function () {
        updatePaymentSelectionSummary();

        const total = $('.payment-checkbox').length;
        const checked = $('.payment-checkbox:checked').length;
        $('#selectAllPayments').prop('checked', total > 0 && total === checked);
    });

    $('#newCustomerBookingId').on('change', function () {
        let customerBookingId = $(this).val();

        $('#newPlotSaleDetailId').html('<option value="">Loading...</option>');

        if (!customerBookingId) {
            $('#newPlotSaleDetailId').html('<option value="">Select Plot Booking</option>');
            return;
        }

        $.get(`/payment-transfer/customer-plots/${customerBookingId}`, function (plots) {
            let options = '<option value="">Select Plot Booking</option>';
            const filteredPlots = plots.filter(function (plot) {
                return !isSameSourceTarget(customerBookingId, plot.id);
            });

            if (filteredPlots.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Plot Booking',
                    text: 'The selected customer does not have another plot booking available for transfer.'
                });
            }

            $.each(filteredPlots, function (index, plot) {
                options += `<option value="${plot.id}">${plot.name}</option>`;
            });

            $('#newPlotSaleDetailId').html(options);
        });
    });

    $('#transferPaymentBtn').on('click', function () {
        let paymentIds = [];

        $('.payment-checkbox:checked').each(function () {
            paymentIds.push($(this).val());
        });

        let newCustomerBookingId = $('#newCustomerBookingId').val();
        let newPlotSaleDetailId = $('#newPlotSaleDetailId').val();

        if (paymentIds.length === 0) {
            Swal.fire('Error', 'Please select at least one payment.', 'error');
            return;
        }

        if (!newCustomerBookingId) {
            Swal.fire('Error', 'Please select customer.', 'error');
            return;
        }

        if (!newPlotSaleDetailId) {
            Swal.fire('Error', 'Please select plot booking.', 'error');
            return;
        }

        if (isSameSourceTarget(newCustomerBookingId, newPlotSaleDetailId)) {
            Swal.fire('Error', 'Payment cannot be transferred to the same customer and same plot.', 'error');
            return;
        }

        Swal.fire({
            title: 'Transfer Payments?',
            text: `${paymentIds.length} selected payment entries will be moved to selected plot booking.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes Transfer',
            confirmButtonColor: '#198754'
        }).then((result) => {

            if (result.isConfirmed) {
                setPaymentTransferLoading(true);
                $.ajax({
                    url: "{{ route('payment-transfer.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        payment_ids: paymentIds,
                        new_customer_booking_id: newCustomerBookingId,
                        new_plot_sale_detail_id: newPlotSaleDetailId,
                        transfer_date: $('#transferDate').val(),
                        transfer_reason: $('#transferReason').val(),
                        remark: $('#remark').val()
                    },
                    success: function (res) {
                        Swal.fire('Success', res.message, 'success')
                            .then(() => location.reload());
                    },
                    error: function (xhr) {
                        setPaymentTransferLoading(false);
                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Payment transfer failed.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    if ($('#paymentTransferHistoryTable tbody tr td').attr('colspan') === undefined) {
        $('#paymentTransferHistoryTable').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']]
        });
    }
});

function renderPayments(payments)
{
    let html = '';

    if (!payments || payments.length === 0) {
        html = `
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    No payment found.
                </td>
            </tr>
        `;
    } else {
        $.each(payments, function (index, payment) {
            html += `
                <tr>
                    <td>
                        <input type="checkbox"
                            class="form-check-input payment-checkbox"
                            value="${payment.id}"
                            data-amount="${payment.paid_amount_raw || 0}">
                    </td>
                    <td>
                        <strong>${payment.receipt_number}</strong>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            ${payment.plot_no || '-'}
                        </span>
                    </td>
                    <td>${payment.date}</td>
                    <td>${payment.plan_type}</td>
                    <td>${payment.transaction_category}</td>
                    <td>${payment.payment_mode}</td>
                    <td>
                        <span class="badge ${payment.booking_status === 'Booked' ? 'bg-success' : 'bg-warning text-dark'}">
                            ${payment.booking_status}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${payment.payment_status === 'Cleared' ? 'bg-success' : 'bg-warning text-dark'}">
                            ${payment.payment_status}
                        </span>
                    </td>
                    <td class="fw-bold text-success text-end">Rs. ${payment.paid_amount}</td>
                </tr>
            `;
        });
    }

    $('#paymentListBody').html(html);
    updatePaymentSelectionSummary();
}

function loadCustomers()
{
    $.get(`/payment-transfer/customers`, function (customers) {
        let options = '<option value="">Select Customer</option>';

        $.each(customers, function (index, customer) {
            options += `<option value="${customer.id}">${customer.name}</option>`;
        });

        $('#newCustomerBookingId').html(options);
    });
}

function resetAll(clearBlock = true)
{
    if (clearBlock) {
        $('#blockId').html('<option value="">Select Block</option>');
    }

    $('#plotId').html('<option value="">Select Plot</option>');
    clearPaymentSection();
}

function clearPaymentSection()
{
    $('#sourceBookingCode').val('');
    $('#sourceCustomerCode').val('');
    $('#sourceCustomerName').val('');
    $('#sourceProject').val('');
    $('#sourceBlock').val('');
    $('#sourcePlot').val('');

    $('#selectAllPayments').prop('checked', false);
    $('#paymentListBody').html(`
        <tr>
            <td colspan="10" class="text-center text-muted py-4">
                No payment found.
            </td>
        </tr>
    `);

    $('#newCustomerBookingId').html('<option value="">Select Customer</option>');
    $('#newPlotSaleDetailId').html('<option value="">Select Plot Booking</option>');
    $('#transferReason').val('');
    $('#remark').val('');

    $('#sourceDetailsCard').addClass('d-none');
    $('#paymentListCard').addClass('d-none');
    $('#transferCard').addClass('d-none');
    sourcePaymentTransfer = {
        customerBookingId: null,
        plotSaleIds: []
    };
    updatePaymentSelectionSummary();
}

function setPaymentTransferLoading(isLoading)
{
    const button = $('#transferPaymentBtn');
    button.prop('disabled', isLoading);
    button.find('.btn-label').toggleClass('d-none', isLoading);
    button.find('.btn-loader').toggleClass('d-none', !isLoading);
}

function updatePaymentSelectionSummary()
{
    let selectedAmount = 0;
    const selected = $('.payment-checkbox:checked');

    selected.each(function () {
        selectedAmount += parseFloat($(this).data('amount')) || 0;
    });

    $('#selectedPaymentCount').text(selected.length);
    $('#selectedPaymentAmount').text(formatAmount(selectedAmount));
}

function formatAmount(value)
{
    return Number(value || 0).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function isSameSourceTarget(customerBookingId, plotSaleId)
{
    return String(customerBookingId || '') === String(sourcePaymentTransfer.customerBookingId || '')
        && sourcePaymentTransfer.plotSaleIds.includes(String(plotSaleId || ''));
}
</script>
@endpush
