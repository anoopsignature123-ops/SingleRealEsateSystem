@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <div class="mb-4">
            <h3 class="fw-bold">
                Associate Tree
            </h3>

            <small class="text-muted">
                Organization hierarchy
            </small>
        </div>

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                {{-- Search --}}
                <form method="GET" class="mb-5">

                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <input type="text" name="associate_id" placeholder="Enter associate id"
                                value="{{ request('associate_id') }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success">
                                Show
                            </button>
                            <a href="{{ route('admin.associate-tree') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>


                @if ($rootAssociate)
                    <div class="tree-container">

                        <div class="org-chart-wrapper">

                            @include('associate-tree.node', [
                                'associate' => $rootAssociate,
                            ])

                        </div>

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
