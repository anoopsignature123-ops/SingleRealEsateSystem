@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    PLC Rate Master
                </h3>
                <small class="text-muted">
                    Manage PLC Rates
                </small>
            </div>
            <a href="{{ route('admin.plc-rates.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Add PLC Rate
            </a>
        </div>
        <div class="card shadow-sm border-0">
            @include('partials.master-header')
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="plcRateTable">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Plot Type</th>
                                <th>PLC Rate (%)</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plcRates as $key => $plcRate)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ ucfirst($plcRate->plotType?->plot_type_name) }}
                                    </td>
                                    <td>
                                        {{ number_format($plcRate->rate, 2, '.', '') }}
                                    </td>
                                    <td>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.plc-rates.edit', $plcRate->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.plc-rates.destroy', $plcRate->id) }}" method="POST"
                                            class="d-inline">
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
                                    <td colspan="4" class="text-center">
                                        No Data Found
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
            if ($('#plcRateTable tbody tr td[colspan]').length == 0) {
                $('#plcRateTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    ordering: true,
                    searching: true
                });
            }
            $('.delete-btn').click(function() {
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This record will be deleted.',
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
