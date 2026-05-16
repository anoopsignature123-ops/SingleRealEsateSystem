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

                    <table class="table table-hover align-middle" id="associateReportTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Sponsor ID</th>

                                <th>Agent ID</th>

                                <th>Name</th>

                                <th>Mobile</th>

                                <th>Date</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($agents as $key => $agent)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $agent->sponsor_id }}
                                    </td>

                                    <td>
                                        {{ $agent->associate_id }}
                                    </td>

                                    <td>
                                        {{ $agent->associate_name }}
                                    </td>

                                    <td>
                                        {{ $agent->mobile_number }}
                                    </td>

                                    <td>
                                        {{ $agent->created_at->format('d-M-Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center text-muted py-4">

                                        No Record Found

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
