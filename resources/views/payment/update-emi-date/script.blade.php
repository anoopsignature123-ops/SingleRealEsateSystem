@push('scripts')
    <script>
        $(document).ready(function() {

            function toggleBulkButton() {
                let selectedIds = [];

                $('.payment_checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#bulk_update_btn').removeClass('d-none');
                    $('#payment_ids').val(selectedIds.join(','));
                } else {
                    $('#bulk_update_btn').addClass('d-none');
                    $('#payment_ids').val('');
                }
            }

            $('#select_all').on('change', function() {
                $('.payment_checkbox').prop(
                    'checked',
                    $(this).is(':checked')
                );

                toggleBulkButton();
            });

            $(document).on('change', '.payment_checkbox', function() {
                toggleBulkButton();

                let totalCheckbox = $('.payment_checkbox').length;
                let checkedCheckbox = $('.payment_checkbox:checked').length;

                $('#select_all').prop(
                    'checked',
                    totalCheckbox > 0 && totalCheckbox === checkedCheckbox
                );
            });

            $('#bulkDateModal').on('show.bs.modal', function() {
                toggleBulkButton();
            });

            if ($('#emiDateTable tbody tr td').attr('colspan') === undefined) {
                $('#emiDateTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                });
            }

        });
    </script>
@endpush
