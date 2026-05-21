<div class="row g-4">
    {{-- Project Selection --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary mb-2">Project <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-layers"></i>
            </span>
            <select name="project_id" class="form-select rounded-start-0 @error('project_id') is-invalid @enderror"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                <option value="">Select Project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ old('project_id', $block->project_id ?? '') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('project_id')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Block Name --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold text-secondary mb-2">Block Name <span class="text-danger">*</span></label>
        <div class="d-flex align-items-stretch">
            <span class="input-group-text bg-light text-muted px-3 border-end-0 rounded-start rounded-0"
                style="border: 1px solid #ced4da;">
                <i class="bi bi-grid-3x3-gap"></i>
            </span>
            <input type="text" name="block"
                class="form-control rounded-start-0 @error('block') is-invalid @enderror" placeholder="Enter block name"
                value="{{ old('block', $block->block ?? '') }}"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
        </div>
        @error('block')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Form Action Button --}}
<div class="text-end mt-4">
    <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm py-2">
        <i class="bi bi-check-circle me-1"></i> Save Block
    </button>
</div>
