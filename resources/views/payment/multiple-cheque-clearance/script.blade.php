@push('scripts')
    <script>
        $(document).ready(function() {
            let selectedPayments = [];

            function formatAmount(amount) {
                return Number(amount || 0).toFixed(2);
            }

            function setSaveLoading(isLoading) {
                const button = $('#saveChequeStatusBtn');
                button.prop('disabled', isLoading);
                button.find('.btn-label').toggleClass('d-none', isLoading);
                button.find('.btn-loader').toggleClass('d-none', !isLoading);
            }

            function updateBulkButton() {
                selectedPayments = [];
                let selectedAmount = 0;
                let selectedGroups = 0;

                $('.payment_checkbox:checked').each(function() {
                    selectedGroups++;
                    String($(this).val()).split(',').forEach(function(id) {
                        id = id.trim();
                        if (id) selectedPayments.push(id);
                    });
                    selectedAmount += parseFloat($(this).data('amount')) || 0;
                });

                selectedPayments = [...new Set(selectedPayments)];

                $('#payment_ids').val(selectedPayments.join(','));
                $('#selected_count, #modal_selected_count').text(selectedGroups);
                $('#selected_amount').text(formatAmount(selectedAmount));

                const hasSelection = selectedPayments.length > 0;
                $('#bulk_action_btn, #selection_summary').toggleClass('d-none', !hasSelection);
            }

            $('#select_all').on('change', function() {
                $('.payment_checkbox').prop('checked', $(this).is(':checked'));
                updateBulkButton();
            });

            $(document).on('change', '.payment_checkbox', function() {
                updateBulkButton();

                const total = $('.payment_checkbox').length;
                const checked = $('.payment_checkbox:checked').length;
                $('#select_all').prop('checked', total > 0 && total === checked);
            });

            $('#cheque_status').on('change', function() {
                const status = $(this).val();
                $('#reason_box').toggleClass('d-none', !['cancelled', 'bounced', 'pending'].includes(status));
            });

            $('#statusModal').on('show.bs.modal', function(event) {
                if (selectedPayments.length === 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'No records selected',
                        text: 'Please select at least one cheque or DD record.'
                    });
                }
            });

            $('#chequeStatusForm').on('submit', function(event) {
                if (selectedPayments.length === 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'No records selected',
                        text: 'Please select at least one cheque or DD record.'
                    });
                    return;
                }

                setSaveLoading(true);
            });

            $('#cheque_status').trigger('change').select2({
                width: '100%',
                dropdownParent: $('#statusModal')
            });
        });
    </script>
@endpush
