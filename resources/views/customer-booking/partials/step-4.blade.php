@if ($step == 4)
    <form method="POST" action="{{ route('admin.customer-booking.update', $customer->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="step" value="4">
        @include('customer-booking.partials.plot-sale-form')
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('admin.customer-booking.edit', [$customer->id, 'step' => 3]) }}"
                class="btn btn-outline-secondary px-4">Previous
            </a>
            <button type="submit" class="btn btn-success px-4 ms-2">Save & Next</button>
        </div>
    </form>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let selectedProjectId = "{{ old('project_id', $plotSale?->project_id) }}";
                let selectedBlockId = "{{ old('block_id', $plotSale?->block_id) }}";
                let customerId = "{{ $customer->id ?? '' }}";

                function calculateFinalAmount() {
                    let plotCost = parseFloat($('#plotCost').val()) || 0;
                    let plcAmount = parseFloat($('#plcAmount').val()) || 0;
                    let otherCharges = parseFloat($('#otherCharges').val()) || 0;
                    let couponDiscount = parseFloat($('#couponDiscount').val()) || 0;
                    let finalAmount = plotCost + plcAmount + otherCharges - couponDiscount;
                    $('#finalPayable').val(
                        finalAmount.toFixed(2)
                    );
                    $('#totalPlotCost').val(
                        finalAmount.toFixed(2)
                    );
                }

                function loadBlocks(projectId, selectedBlock = '') {
                    if (!projectId) {
                        $('#blockId').html('<option value="">Select Block</option>');
                        $('#showPlots').addClass('d-none');
                        return;
                    }
                    $('#blockId').html('<option value="">Loading...</option>');
                    $.get('/admin/get-blocks/' + projectId, function(blocks) {
                        let html = '<option value="">Select Block</option>';
                        $.each(blocks, function(i, block) {
                            let selected = '';
                            if (selectedBlock == block.id) {
                                selected = 'selected';
                            }
                            html += `
                                 <option value="${block.id}" ${selected}>${block.block}</option>`;
                        });
                        $('#blockId').html(html);
                        if (selectedBlock) {
                            $('#showPlots').removeClass('d-none');
                        }
                    });
                }
                if (selectedProjectId) {
                    loadBlocks(selectedProjectId, selectedBlockId);
                }
                $('#projectId').change(function() {
                    let projectId = $(this).val();
                    $('#plotListSection').html('');
                    $('#showPlots').addClass('d-none');
                    loadBlocks(projectId);
                });
                $('#blockId').change(function() {
                    let blockId = $(this).val();
                    $('#plotListSection').html('');
                    if (blockId) {
                        $('#showPlots').removeClass('d-none');
                    } else {
                        $('#showPlots').addClass('d-none');
                    }
                });

                $('#showPlots').click(function() {
                    let blockId = $('#blockId').val();
                    if (!blockId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Block Required',
                            text: 'Please select block first.'
                        });
                        return;
                    }
                    let plotsUrl = '/admin/get-plots/' + blockId;
                    if (customerId) {
                        plotsUrl += '/' + customerId;
                    }
                    $.get(plotsUrl, function(plots) {
                        if (plots.length === 0) {
                            let html = `
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>⚠️ No Plots Available!</strong>
                                    <p>All plots in this block have been booked. Please select a different block or project.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('#plotListSection').html(html);
                            return;
                        }
                        let html = '<div class="row">';
                        $.each(plots, function(i, plot) {
                            let plotType = 'N/A';
                            if (plot.plot_type) {
                                plotType = plot.plot_type.plot_type_name;
                            }
                            html += `
                                <div class="col-md-3 mb-3">
                                    <div class="card plot-card shadow-sm border"
                                        style="cursor:pointer"
                                        data-id="${plot.id}"
                                        data-number="${plot.plot_number}"
                                        data-rate="${plot.plot_rate}"
                                        data-area="${plot.plot_area}"
                                        data-plc="${plot.plc_rate}">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-success">Plot ${plot.plot_number}</h6>
                                            <div>
                                                Rate:₹${parseFloat(plot.plot_rate).toFixed(2)}
                                            </div>
                                            <div>
                                                Area:${parseFloat(plot.plot_area).toFixed(2)} Sq.Ft
                                            </div>
                                            <div>Type:${plotType}</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        $('#plotListSection').html(html);
                    });
                });
                $(document).on('click', '.plot-card', function() {
                    $('.plot-card').removeClass('border-success');
                    $(this).addClass('border-success');
                    let id = $(this).data('id');
                    let number = $(this).data('number');
                    let rate = parseFloat($(this).data('rate')) || 0;
                    let area = parseFloat($(this).data('area')) || 0;
                    let plc = parseFloat($(this).data('plc')) || 0;
                    let plotCost = rate * area;
                    $('#plotId').val(id);
                    $('#plotNumber').val(number);
                    $('#plotRate').val(rate.toFixed(2));
                    $('#plotArea').val(area.toFixed(2));
                    $('#plcAmount').val(plc.toFixed(2));
                    $('#plotCost').val(plotCost.toFixed(2));
                    calculateFinalAmount();
                });
                $('#otherCharges, #couponDiscount').on('keyup change', function() {
                    calculateFinalAmount();
                });
            });
        </script>
    @endpush
@endif
