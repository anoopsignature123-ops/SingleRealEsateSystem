<script>
$(document).ready(function () {

    function resetFields() {
        $('.emi-field').addClass('d-none');
        $('.bank-field').addClass('d-none');
        $('.cheque-field').addClass('d-none');
        $('.dd-field').addClass('d-none');
        $('.transaction-field').addClass('d-none');
    }

    function toggleFields() {
        resetFields();

        let planType = $('#planType').val();
        let paymentMode = $('#paymentMode').val();

        if (planType === 'emi_plan') {
            $('.emi-field').removeClass('d-none');
        }

        if (['cheque', 'dd', 'neft_rtgs', 'card'].includes(paymentMode)) {
            $('.bank-field').removeClass('d-none');
        }

        if (paymentMode === 'cheque') {
            $('.cheque-field').removeClass('d-none');
        }

        if (paymentMode === 'dd') {
            $('.dd-field').removeClass('d-none');
        }

        if (['neft_rtgs', 'card'].includes(paymentMode)) {
            $('.transaction-field').removeClass('d-none');
        }
    }

    function calculateAmounts() {
        let total = parseFloat($('#totalPlotCost').val()) || 0;
        let paid = parseFloat($('#paidAmount').val()) || 0;
        let months = parseInt($('#emiMonths').val()) || 0;

        let due = total - paid;

        if (due < 0) {
            due = 0;
        }

        $('#dueAmount').val(due.toFixed(2));

        if ($('#planType').val() === 'emi_plan' && months > 0) {
            $('#emiAmount').val((due / months).toFixed(2));
        } else {
            $('#emiAmount').val('');
        }
    }

    $('#planType').on('change', function () {
        toggleFields();
        calculateAmounts();
    });

    $('#paymentMode').on('change', toggleFields);

    $('#paidAmount, #emiMonths').on('keyup change', calculateAmounts);

    toggleFields();
    calculateAmounts();

});
</script>