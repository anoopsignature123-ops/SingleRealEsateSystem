<div class="row">

    {{-- Project --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Project Name</label>

        <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror">

            <option value="">Select Project</option>

            @foreach ($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $plotDetail->project_id ?? '') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach

        </select>

        @error('project_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Location --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Location</label>

        <input type="text" name="location" id="location" readonly class="form-control"
            placeholder="Project location" value="{{ old('location', $plotDetail->location ?? '') }}">
    </div>


    {{-- Number Of Plots --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Number Of Plots</label>

        <input type="number" name="number_of_plots" class="form-control @error('number_of_plots') is-invalid @enderror"
            placeholder="Enter number of plots"
            value="{{ old('number_of_plots', $plotDetail->number_of_plots ?? '') }}">

        @error('number_of_plots')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Block --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Block</label>

        <select name="block_id" id="block_id" class="form-select @error('block_id') is-invalid @enderror">

            <option value="">Select Block</option>

        </select>

        @error('block_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Plot Type --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Plot Type</label>

        <select name="plot_type_id" id="plot_type_id" class="form-select @error('plot_type_id') is-invalid @enderror">

            <option value="">Select Plot Type</option>

            @foreach ($plotTypes as $type)
                <option value="{{ $type->id }}"
                    {{ old('plot_type_id', $plotDetail->plot_type_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->plot_type_name }}
                </option>
            @endforeach

        </select>

        @error('plot_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Plot Number --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Plot Number</label>

        <input type="text" name="plot_number" 
            class="form-control @error('plot_number') is-invalid @enderror" placeholder="Enter plot number"
            value="{{ old('plot_number', $plotDetail->plot_number ?? '') }}">

        @error('plot_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Plot Range --}}
    <div id="plotRangeSection" style="display:none;">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Plot No (From)</label>

                <input type="text" name="plot_no_from" class="form-control" placeholder="Enter starting plot number"
                    value="{{ old('plot_no_from', $plotDetail->plot_no_from ?? '') }}">
            </div>


            <div class="col-md-6 mb-3">
                <label class="form-label">Plot No (To)</label>

                <input type="text" name="plot_no_to" class="form-control" placeholder="Enter ending plot number"
                    value="{{ old('plot_no_to', $plotDetail->plot_no_to ?? '') }}">
            </div>

        </div>

    </div>


    {{-- Plot Rate --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Plot Rate</label>

        <input type="number" name="plot_rate" id="plot_rate" class="form-control" placeholder="Enter plot rate"
            value="{{ old('plot_rate', $plotDetail->plot_rate ?? '') }}">
    </div>


    {{-- PLC --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">PLC Rate (%)</label>

        <input type="text" name="plc_rate" id="plc_rate" readonly class="form-control" placeholder="Auto calculated"
            value="{{ old('plc_rate', $plotDetail->plc_rate ?? '') }}">
    </div>


    {{-- Plot Area --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Plot Area</label>

        <input type="text" name="plot_area" id="plot_area" class="form-control" placeholder="Example: 1200 sq.ft"
            value="{{ old('plot_area', $plotDetail->plot_area ?? '') }}">
    </div>


    {{-- Status --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>

        <select name="status" class="form-select">
            <option value="">Select Status</option>
            <option value="available">Available</option>
            <option value="booked">Booked</option>
            <option value="hold">Hold</option>
            <option value="registry">Registry</option>
        </select>
    </div>
    {{-- Width + Length --}}
    <div id="plotDimensionSection" style="display:none;">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Width (ft)</label>

                <input type="text" name="plot_width" class="form-control" placeholder="Enter width"
                    value="{{ old('plot_width', $plotDetail->plot_width ?? '') }}">
            </div>


            <div class="col-md-6 mb-3">
                <label class="form-label">Length (ft)</label>

                <input type="text" name="plot_length" class="form-control" placeholder="Enter length"
                    value="{{ old('plot_length', $plotDetail->plot_length ?? '') }}">
            </div>

        </div>

    </div>


    {{-- Button --}}
    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-success px-4">
            Save
        </button>
    </div>

</div>



@push('scripts')
    <script>
        $(function() {

            // Project Data Load
            function loadProjectData(projectId, selectedBlock = '') {

                if (projectId == '') {
                    $('#location').val('');
                    $('#block_id').html('<option value="">Select Block</option>');
                    return;
                }

                $.get('/admin/get-project-data/' + projectId, function(response) {

                    $('#location').val(response.location);

                    let html = '<option value="">Select Block</option>';

                    $.each(response.blocks, function(i, block) {

                        let selected = selectedBlock == block.id ? 'selected' : '';

                        html += `
                    <option value="${block.id}" ${selected}>
                        ${block.block}
                    </option>
                `;
                    });

                    $('#block_id').html(html);

                });
            }


            $('#project_id').change(function() {
                loadProjectData($(this).val());
            });


            // Edit Mode
            let projectId = $('#project_id').val();

            if (projectId) {
                loadProjectData(
                    projectId,
                    "{{ old('block_id', $plotDetail->block_id ?? '') }}"
                );
            }



            // PLC Calculation
            function calculatePlc() {

                let rate = parseFloat($('#plot_rate').val()) || 0;

                let plc = (rate * 5) / 100;

                $('#plc_rate').val(plc.toFixed(2));
            }

            $('#plot_rate').on('keyup change', calculatePlc);

            calculatePlc();



            // Plot Type Logic
            function togglePlotRange() {

                let text = $('#plot_type_id option:selected')
                    .text()
                    .trim()
                    .toLowerCase();

                if (text == 'normal') {

                    $('#plotRangeSection').slideDown();

                    $('#plot_number').prop('readonly', true);

                } else {

                    $('#plotRangeSection').slideUp();

                    $('#plot_number').prop('readonly', false);
                }
            }

            $('#plot_type_id').change(togglePlotRange);

            togglePlotRange();



            // Plot Dimension Logic
            function toggleDimension() {

                let area = $('#plot_area').val().trim();

                if (area != '') {

                    $('#plotDimensionSection').slideDown();

                } else {

                    $('#plotDimensionSection').slideUp();
                }
            }

            $('#plot_area').on('keyup change', toggleDimension);

            toggleDimension();

        });
    </script>
@endpush
