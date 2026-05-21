<div class="row g-4">
    {{-- Project --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold text-secondary mb-2">Project <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-layers"></i>
            </span>
            <select name="project_id" id="project_id"
                class="form-select rounded-start-0 @error('project_id') is-invalid @enderror"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                <option value="">Select Project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ old('project_id', $plotRate->project_id ?? '') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('project_id')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Block --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold text-secondary mb-2">Block <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-grid-3x3-gap"></i>
            </span>
            <select name="block_id" id="block_id"
                class="form-select rounded-start-0 @error('block_id') is-invalid @enderror"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                <option value="">Select Block</option>
            </select>
        </div>
        @error('block_id')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Plot Rate --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold text-secondary mb-2">Plot Rate <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-currency-rupee"></i>
            </span>
            <input type="number" name="plot_rate" id="plot_rate"
                class="form-control rounded-start-0 @error('plot_rate') is-invalid @enderror"
                placeholder="Enter plot rate" value="{{ old('plot_rate', $plotRate->plot_rate ?? '') }}"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
        </div>
        @error('plot_rate')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Form Action Button --}}
<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm py-2">
        <i class="bi bi-check-circle me-1"></i> Save Plot Rate
    </button>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadBlocks(projectId, selectedBlock = '') {
                if (!projectId) {
                    $('#block_id').html('<option value="">Select Block</option>');
                    return;
                }
                $.ajax({
                    url: "/get-project-data/" + projectId,
                    type: "GET",
                    success: function(response) {
                        $('#block_id').html('<option value="">Select Block</option>');
                        $.each(response.blocks, function(index, block) {
                            let selected = selectedBlock == block.id ? 'selected' : '';
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
                loadBlocks($(this).val());
            });

            let selectedProject = $('#project_id').val();
            let selectedBlock = "{{ old('block_id', $plotRate->block_id ?? '') }}";

            if (selectedProject) {
                loadBlocks(selectedProject, selectedBlock);
            }
        });
    </script>
@endpush
