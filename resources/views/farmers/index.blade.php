@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">
                            <i class="bi bi-people-fill me-2 text-success"></i> Farmer Management
                        </h3>
                        <p class="text-muted mb-0 small">Manage, view, and update farmer accounts.</p>
                    </div>

                    @can('farmers-modify')
                        <a href="{{ route('farmers.create') }}" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New Farmer
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- TABLE SECTION --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="farmersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Broker</th>
                                <th>Farmer Name</th>
                                <th>Caste</th>
                                <th>Mobile</th>
                                <th>PAN</th>
                                <th>Aadhaar</th>
                                <th>Bank Name</th>
                                <th>Account</th>
                                <th>IFSC</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($farmers as $key => $farmer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $farmer->broker->name ?? 'N/A' }}</td>
                                    <td class="fw-semibold">{{ $farmer->name }}</td>
                                    <td>{{ $farmer->caste ?? 'N/A' }}</td>
                                    <td>{{ $farmer->mobile_number ?? 'N/A' }}</td>
                                    <td>{{ $farmer->pancard_number ?? 'N/A' }}</td>
                                    <td class="font-monospace text-muted">[Redacted]</td>
                                    <td>{{ $farmer->bankDetail->bank_name ?? 'N/A' }}</td>
                                    <td>{{ $farmer->bankDetail->account_number ?? 'N/A' }}</td>
                                    <td class="text-uppercase">{{ $farmer->bankDetail->ifsc_code ?? 'N/A' }}</td>
                                    <td>{{ $farmer->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                    <td class="text-center">...actions...</td>
                                </tr>
                            @endforeach
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
            $('#farmersTable').DataTable({
                pageLength: 10,
                ordering: true,
                columnDefs: [{
                    orderable: false,
                    targets: [11]
                }],
                language: {
                    searchPlaceholder: "Search farmers..."
                }
            });

            $(document).on('click', '.delete-btn', function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete It!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush
