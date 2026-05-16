@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold mb-1">
                    Block Management
                </h3>

                <small class="text-muted">
                    Manage all project blocks
                </small>

            </div>

            <a href="{{ route('admin.blocks.create') }}" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>

                Add Block

            </a>

        </div>


        <!-- Card -->
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="blocksTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Project Name</th>

                                <th>Block Name</th>

                                <th width="150">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($blocks as $key => $block)
                                <tr>

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        {{ $block->project?->name }}
                                    </td>

                                    <td>
                                        {{ ucfirst($block->block) }}
                                    </td>

                                    <td>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.blocks.edit', $block->id) }}"
                                            class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.blocks.destroy', $block->id) }}"
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

                                    <td colspan="4" class="text-center text-muted py-4">

                                        No blocks found

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
                $('#blocksTable tbody tr').length > 0 &&
                $('#blocksTable tbody tr td').attr('colspan') == undefined
            ) {

                $('#blocksTable').DataTable({

                    pageLength: 10,

                    ordering: true,

                    searching: true,

                    responsive: true,

                    lengthMenu: [5, 10, 25, 50],

                    language: {

                        search: "",

                        searchPlaceholder: "Search block..."

                    }

                });

            }


            $('.delete-btn').click(function() {

                let form = $(this).closest('form');

                Swal.fire({

                    title: 'Are you sure?',

                    text: "This block will be deleted!",

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonColor: '#198754',

                    cancelButtonColor: '#dc3545',

                    confirmButtonText: 'Yes, delete it!'

                }).then((result) => {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });
    </script>
@endpush
