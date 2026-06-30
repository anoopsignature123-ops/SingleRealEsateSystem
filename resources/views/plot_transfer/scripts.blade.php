@push('scripts')
<script>
let currentBookingData = null;

$(document).ready(function () {
    $('#projectId').on('change', function () {

        let projectId = $(this).val();

        clearSelection();

        $('#blockId').html('<option value="">Loading...</option>');
        $('#plotSaleId').html('<option value="">Select Plot</option>');

        if (!projectId) {
            $('#blockId').html('<option value="">Select Block</option>');
            return;
        }

        $.get(`/plot-transfer/blocks/${projectId}`, function (res) {

            let options = '<option value="">Select Block</option>';

            $.each(res, function (index, block) {
                options += `
                    <option value="${block.id}">
                        ${block.block}
                    </option>
                `;
            });

            $('#blockId').html(options);
        });
    });
    $('#blockId').on('change', function () {

        let blockId = $(this).val();

        clearSelection();

        $('#plotSaleId').html('<option value="">Loading...</option>');

        if (!blockId) {
            $('#plotSaleId').html('<option value="">Select Plot</option>');
            return;
        }

        $.get(`/plot-transfer/plots/${blockId}`, function (res) {

            let options = '<option value="">Select Plot</option>';

            if (res.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Plot Found',
                    text: 'No booked plot was found in this block for transfer.'
                });
            }

            $.each(res, function (index, plot) {
                options += `
                    <option value="${plot.id}">
                        ${plot.plot_number}
                    </option>
                `;
            });

            $('#plotSaleId').html(options);
        });
    });
    $('#plotSaleId').on('change', function () {

        let plotId = $(this).val();

        clearSelection(false);

        if (!plotId) {
            return;
        }

        $.get(`/plot-transfer/booking/${plotId}`, function (r) {

            if (!r) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Booking Not Found',
                    text: 'Booking details were not found for the selected plot.'
                });
                return;
            }

            $('#bookingCode').val(r.booking_id);
            $('#customerCode').val(r.customer_id);
            $('#customerName').val(r.customer_name);

            $('#customerBookingId').val(r.customer_booking_id);
            $('#plotSaleDetailId').val(r.plot_sale_id);

            $('#transferSection').removeClass('d-none');
            $('#currentOwner').val(r.customer_name);

            loadTransferCustomers(r.customer_booking_id);
            currentBookingData = r;
            renderBookingDetails(r);

            $('#bookingDetailsCard').removeClass('d-none');
        });
    });
    $('#transferBtn').on('click', function () {

        let customerBookingId = $('#customerBookingId').val();
        let plotSaleDetailId = $('#plotSaleDetailId').val();
        let newCustomerBookingId = $('#newCustomerBookingId').val();
        let transferCharge = $('#transferCharge').val();
        let transferDate = $('#transferDate').val();
        let transferReason = $('#transferReason').val();
        let selectedPlotSaleIds = getSelectedPlotSaleIds();

        if (!plotSaleDetailId) {
            Swal.fire('Error', 'Please select plot first.', 'error');
            return;
        }

        if (!newCustomerBookingId) {
            Swal.fire('Error', 'Please select customer.', 'error');
            return;
        }

        if (selectedPlotSaleIds.length === 0) {
            Swal.fire('Error', 'Please select at least one plot for transfer.', 'error');
            return;
        }

        Swal.fire({
            title: 'Transfer Plot?',
            text: `${selectedPlotSaleIds.length} selected plot(s) ownership will be transferred. Are you sure?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes Transfer',
            confirmButtonColor: '#198754'
        }).then((result) => {

            if (result.isConfirmed) {
                setTransferLoading(true);

                $.ajax({
                    url: "{{ route('plot-transfer.store') }}",
                    type: "POST",

                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_booking_id: customerBookingId,
                        plot_sale_detail_id: plotSaleDetailId,
                        plot_sale_detail_ids: selectedPlotSaleIds,
                        new_customer_booking_id: newCustomerBookingId,
                        transfer_charge: transferCharge,
                        transfer_date: transferDate,
                        transfer_reason: transferReason
                    },

                    success: function (res) {
                        Swal.fire(
                            'Success',
                            res.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },

                    error: function (xhr) {
                        setTransferLoading(false);
                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Transfer failed.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $('#transferCharge').on('input change blur', function () {
        $(this).val(sanitizeAmount($(this).val()));
    });

    $(document).on('change', '.transfer-plot-checkbox', function () {
        if (currentBookingData) {
            updateSelectedPlotSummary(currentBookingData);
        }
    });

});

function loadTransferCustomers(bookingId)
{
    $.get(`/plot-transfer/customers/${bookingId}`, function (customers) {

        let options = '<option value="">Select Customer</option>';

        $.each(customers, function (index, customer) {
            options += `
                <option value="${customer.id}">
                    ${customer.name}
                </option>
            `;
        });

        $('#newCustomerBookingId').html(options);
    });
}
function renderBookingDetails(r)
{
    let paymentStatusClass =
        r.payment_status.toLowerCase() === 'cleared'
            ? 'bg-success'
            : 'bg-warning text-dark';

    let bookingStatusClass =
        r.booking_status.toLowerCase() === 'booked'
            ? 'bg-success'
            : 'bg-warning text-dark';

    const planBadge = r.plan_type === 'emi_plan'
        ? '<span class="badge bg-primary">EMI Plan</span>'
        : (r.plan_type === 'mixed'
            ? '<span class="badge bg-warning text-dark">Mixed Plan</span>'
            : '<span class="badge bg-success">Full Payment</span>');

    const plotRows = (r.plots || []).map(function(plot) {
        return `
            <tr>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input transfer-plot-checkbox"
                        value="${plot.plot_sale_id}" checked
                        data-cost="${parseAmount(plot.total_cost)}">
                </td>
                <td>
                    <strong>${plot.plot_number}</strong>
                    <small class="text-muted d-block">${plot.project} / Block ${plot.block}</small>
                </td>
                <td>${plot.area} Sq.Ft.</td>
                <td>Rs. ${plot.rate}</td>
                <td class="fw-bold">Rs. ${plot.total_cost}</td>
            </tr>
        `;
    }).join('');

    $('#bookingDetailsContent').html(`
        <div class="p-3">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="transaction-summary-box h-100">
                        <small class="text-muted fw-bold text-uppercase">Plots</small>
                        <h5 class="fw-bold mb-0 text-success" id="selectedPlotCount">${r.plot_count || 1}</h5>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="transaction-summary-box h-100">
                        <small class="text-muted fw-bold text-uppercase">Total Cost</small>
                        <h6 class="fw-bold mb-0">Rs. <span id="selectedTotalCost">${r.total_plot_cost}</span></h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="transaction-summary-box h-100">
                        <small class="text-muted fw-bold text-uppercase">Total Paid</small>
                        <h6 class="fw-bold mb-0 text-success">Rs. ${r.total_paid}</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="transaction-summary-box h-100">
                        <small class="text-muted fw-bold text-uppercase">Balance Due</small>
                        <h6 class="fw-bold mb-0 text-danger">Rs. ${r.remaining_amount}</h6>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="54" class="text-center">Pick</th>
                            <th>Plot</th>
                            <th>Area</th>
                            <th>Rate</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>${plotRows}</tbody>
                </table>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <small class="text-muted fw-bold text-uppercase d-block">Plan Type</small>
                    ${planBadge}
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold text-uppercase d-block">Payment Status</small>
                    <span class="badge ${paymentStatusClass}">${r.payment_status}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold text-uppercase d-block">Booking Status</small>
                    <span class="badge ${bookingStatusClass}">${r.booking_status}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold text-uppercase d-block">Payment Mode</small>
                    <span class="badge bg-light text-dark border">${r.payment_mode}</span>
                </div>
            </div>

            ${
                r.plan_type === 'emi_plan'
                ? `
                    <div class="alert alert-primary mt-3 mb-0">
                        <strong>EMI Summary:</strong>
                        Total EMI ${r.emi_months}, Paid EMI ${r.paid_emis}, Due EMI ${r.due_months}
                    </div>
                `
                : ''
            }
        </div>
    `);

    updateSelectedPlotSummary(r);
}
function clearSelection(clearPlot = true)
{
    $('#bookingCode').val('');
    $('#customerCode').val('');
    $('#customerName').val('');

    $('#customerBookingId').val('');
    $('#plotSaleDetailId').val('');

    $('#currentOwner').val('');

    $('#newCustomerBookingId').html(
        '<option value="">Select Customer</option>'
    );

    $('#transferCharge').val(0);
    $('#transferReason').val('');

    $('#bookingDetailsCard').addClass('d-none');
    $('#transferSection').addClass('d-none');
    currentBookingData = null;

    if (clearPlot) {
        $('#plotSaleId').html('<option value="">Select Plot</option>');
    }
}

function getSelectedPlotSaleIds()
{
    return $('.transfer-plot-checkbox:checked').map(function () {
        return $(this).val();
    }).get();
}

function updateSelectedPlotSummary(r)
{
    const selected = $('.transfer-plot-checkbox:checked');
    let selectedCost = 0;

    selected.each(function () {
        selectedCost += parseFloat($(this).data('cost')) || 0;
    });

    $('#selectedPlotCount').text(selected.length);
    $('#selectedTotalCost').text(formatAmount(selectedCost));

    const selectedIds = getSelectedPlotSaleIds();
    $('#plotSaleDetailId').val(selectedIds[0] || r.plot_sale_id);
    $('#bookingCode').val(r.booking_id);
}

function parseAmount(value)
{
    return parseFloat(String(value || '0').replace(/,/g, '')) || 0;
}

function formatAmount(value)
{
    return Number(value || 0).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function setTransferLoading(isLoading)
{
    const button = $('#transferBtn');
    button.prop('disabled', isLoading);
    button.find('.btn-label').toggleClass('d-none', isLoading);
    button.find('.btn-loader').toggleClass('d-none', !isLoading);
}

function sanitizeAmount(value)
{
    value = String(value || '').replace(/[^\d.]/g, '');
    const firstDot = value.indexOf('.');

    if (firstDot !== -1) {
        value = value.substring(0, firstDot + 1) + value.substring(firstDot + 1).replace(/\./g, '');
    }

    return value;
}
</script>
@endpush
