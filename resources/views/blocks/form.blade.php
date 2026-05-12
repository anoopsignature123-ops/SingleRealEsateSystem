<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Project
        </label>

        <select name="project_id" class="form-select">

            <option value="">
                Select Project
            </option>

            @foreach ($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id', $block->project_id ?? '') == $project->id ? 'selected' : '' }}>

                    {{ $project->name }}

                </option>
            @endforeach

        </select>

        @error('project_id')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            Block Name
        </label>

        <input type="text" name="block" class="form-control" placeholder="Enter block name"
            value="{{ old('block', $block->block ?? '') }}">

        @error('block')
            <small class="text-danger">
                {{ $message }}
            </small>
        @enderror

    </div>

</div>

<button class="btn btn-success">
    Save Block
</button>
