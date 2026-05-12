@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Project Manipulation
                </h3>
                <p class="text-muted">
                    Project Manipulation
                </p>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row">

                        <!-- Project -->
                        <div class="col-md-3">

                            <label>
                                Project
                            </label>

                            <select name="project_id" id="project_id" class="form-select">

                                <option value="">
                                    Select Project
                                </option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">

                                        {{ $project->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <!-- Plot -->
                        <div class="col-md-3">

                            <label>
                                Plot
                            </label>

                            <select name="plot_number" id="plot_number" class="form-select">

                                <option value="">
                                    Select Plot
                                </option>

                            </select>

                        </div>


                        <!-- Status -->
                        <div class="col-md-3">

                            <label>
                                Status
                            </label>

                            <select name="status" class="form-select">

                                <option value="">
                                    Select Status
                                </option>

                                <option value="available">
                                    Available
                                </option>

                                <option value="booked">
                                    Booked
                                </option>
                                <option value="hold">
                                    Hold
                                </option>
                                <option value="registry">
                                    Registry
                                </option>

                            </select>

                        </div>


                        <div class="col-md-3 d-flex align-items-end">

                            <button class="btn btn-success">

                                Search Plot

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <div class="text-end">
            <a href="{{ route('admin.project.manipulation.export', array_merge(request()->all(), ['type' => 'excel'])) }}"
                class="btn btn-success shadow-sm px-3">
                <i class="bi bi-file-earmark-excel-fill me-2"></i>
                Export Excel
            </a>
            <a href="{{ route('admin.project.manipulation.export', array_merge(request()->all(), ['type' => 'pdf'])) }}"
                class="btn btn-danger shadow-sm px-3">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                Download PDF
            </a>
        </div>

        <!-- Listing -->
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Project</th>
                            <th>Plot No.</th>
                            <th>Plot Size</th>
                            <th>Update Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($plots as $key => $plot)
                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    {{ $plot->project?->name }}
                                </td>

                                <td>
                                    {{ $plot->plot_number }}
                                </td>

                                <td>
                                    {{ $plot->plot_area }} Sqft
                                </td>
                                <td>
                                    {{ $plot->updated_at?->format('d-m-Y h:i A') }}
                                </td>

                                <td>
                                    <span
                                        class="fw-bold
                                                                {{ $plot->status == 'available'
                                                                    ? 'text-success'
                                                                    : ($plot->status == 'booked'
                                                                        ? 'text-danger'
                                                                        : ($plot->status == 'hold'
                                                                            ? 'text-warning'
                                                                            : 'text-primary')) }}">{{ ucfirst($plot->status) }}</span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.project.manipulation.update.status') }}">
                                        @csrf
                                        <input type="hidden" name="plot_id" value="{{ $plot->id }}">
                                        <select name="status" onchange="this.form.submit()" class="form-select">
                                            <option value="available" {{ $plot->status == 'available' ? 'selected' : '' }}>
                                                Available
                                            </option>
                                            <option value="booked" {{ $plot->status == 'booked' ? 'selected' : '' }}>
                                                Booked
                                            </option>
                                            <option value="hold" {{ $plot->status == 'hold' ? 'selected' : '' }}>
                                                Hold
                                            </option>
                                            <option value="registry" {{ $plot->status == 'registry' ? 'selected' : '' }}>
                                                Registry
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#project_id').change(function() {

            let projectId = $(this).val();

            $.get(
                '/admin/get-project-plots-data/' + projectId,

                function(response) {

                    $('#plot_number').html(
                        '<option value="">Select Plot</option>'
                    );

                    $.each(response, function(index, plot) {

                        $('#plot_number').append(

                            `<option value="${plot.plot_number}">
                            ${plot.plot_number}
                        </option>`

                        );

                    });

                }

            );

        });
    </script>
@endpush
