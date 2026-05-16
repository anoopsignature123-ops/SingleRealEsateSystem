@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    Company Profile
                </h3>
                <small class="text-muted">
                    Manage company details
                </small>
            </div>
            @can('company-profile-create')
                <a href="{{ route('admin.company.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add
                    Company</a>
            @endcan
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover" id="companyTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Website</th>
                                @if (auth()->user()->can('company-profile-edit') || auth()->user()->can('company-profile-delete'))
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $key => $company)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <img src="{{ getFileUrl($company->logo) }}" width="40" height="40"
                                            class="rounded-circle border">
                                    </td>
                                    <td>
                                        {{ $company->name }}
                                    </td>

                                    <td>
                                        {{ $company->email }}
                                    </td>

                                    <td>
                                        {{ $company->contact_no }}
                                    </td>

                                    <td>
                                        {{ $company->website_link }}
                                    </td>

                                    @if (auth()->user()->can('company-profile-edit') || auth()->user()->can('company-profile-delete'))
                                        <td>

                                            @can('company-profile-edit')
                                                <a href="{{ route('admin.company.edit', $company->id) }}"
                                                    class="btn btn-sm btn-outline-primary">

                                                    <i class="bi bi-pencil"></i>

                                                </a>
                                            @endcan


                                            @can('company-profile-delete')
                                                <form method="POST" action="{{ route('admin.company.destroy', $company->id) }}"
                                                    class="d-inline delete-form">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn">

                                                        <i class="bi bi-trash"></i>

                                                    </button>

                                                </form>
                                            @endcan

                                        </td>
                                    @endif

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        No company found

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

            if (
                $('#companyTable tbody tr td')
                .attr('colspan') == undefined
            ) {

                $('#companyTable').DataTable();

            }


            $('.delete-btn').click(function() {

                let form = $(this)
                    .closest('form');

                Swal.fire({

                    title: 'Are you sure?',

                    text: "This company will be deleted.",

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonColor: '#198754',

                    cancelButtonColor: '#dc3545',

                    confirmButtonText: 'Yes'

                }).then((result) => {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });
    </script>
@endpush
