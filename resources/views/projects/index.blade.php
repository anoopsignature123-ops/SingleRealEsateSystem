@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-0">
                    Projects
                </h3>

                <small class="text-muted">
                    Manage all projects
                </small>

            </div>

            <a href="{{ route('admin.projects.create') }}" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>

                Add Project

            </a>

        </div>


        <!-- Table Card -->
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="projectTable">

                        <thead class="table-success">

                            <tr>

                                <th>#</th>

                                <th>Site Name</th>

                                <th>Site Location</th>

                                <th>Date</th>

                                <th width="150">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($projects as $key => $project)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $project->name }}
                                    </td>

                                    <td>
                                        {{ $project->location }}
                                    </td>

                                    <td>
                                        {{ $project->date }}
                                    </td>

                                    <td>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.projects.edit', $project->id) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}"
                                            class="d-inline delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center text-muted py-4">

                                        No project found

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            if ($('#projectTable tbody tr td').attr('colspan') == undefined) {

                $('#projectTable').DataTable({

                    pageLength: 10,

                    ordering: true,

                    responsive: true

                });

            }


            $('.delete-btn').click(function() {

                let form = $(this).closest('form');

                Swal.fire({

                    title: 'Are you sure?',

                    text: "This project will be deleted.",

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonColor: '#198754',

                    cancelButtonColor: '#dc3545',

                    confirmButtonText: 'Yes, delete it'

                }).then((result) => {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });
    </script>
@endpush
