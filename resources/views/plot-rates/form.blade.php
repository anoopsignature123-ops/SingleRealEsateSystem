<div class="row">
    <!-- Project -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Project</label>
        <select name="project_id" id="project_id" class="form-select">
            <option value="">Select</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $plotRate->project_id ?? '') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
        @error('project_id')
            <div class="invalid-feedback d-block">{{ $message }} </div>
        @enderror
    </div>
    <!-- Block -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Block</label>
        <select name="block_id" id="block_id" class="form-select ">
            <option value="">Select</option>
        </select>
        @error('block_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <!-- Plot Rate -->
    <div class="col-md-4 mb-3">
        <label class="form-label">Plot Rate</label>
        <input type="number" name="plot_rate" class="form-control " placeholder="Enter plot rate"
            value="{{ old('plot_rate', $plotRate->plot_rate ?? '') }}">
        @error('plot_rate')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="text-end">
    <button type="submit" class="btn btn-success">Save</button>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadBlocks(projectId, selectedBlock = '') {
                if (!projectId) {
                    $('#block_id').html(
                        '<option value="">Select</option>'
                    );
                    return;
                }
                $.ajax({
                    url: "/admin/get-project-data/" + projectId,
                    type: "GET",
                    success: function(response) {
                        $('#block_id').html(
                            '<option value="">Select</option>'
                        );
                        $.each(response.blocks, function(index, block) {
                            let selected =
                                selectedBlock == block.id ?
                                'selected' :
                                '';
                            $('#block_id').append(`
                        <option value="${block.id}" ${selected}>
                            ${block.block}
                        </option>
                    `);

                        });
                    }
                });
            }
            $('#project_id').change(function() {
                loadBlocks(
                    $(this).val()
                );
            });
            let selectedProject =
                $('#project_id').val();
            let selectedBlock =
                "{{ old('block_id', $plotRate->block_id ?? '') }}";
            if (selectedProject) {
                loadBlocks(
                    selectedProject,
                    selectedBlock
                );
            }
        });
    </script>
@endpush
