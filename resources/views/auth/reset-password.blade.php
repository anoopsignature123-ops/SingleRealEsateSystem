@extends('auth.app')

@section('content')
<div class="d-flex vh-100">

    <div class="col-12 d-flex align-items-center justify-content-center bg-light">
        <div class="w-100 px-4" style="max-width: 400px;">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-success">Reset Password</h3>
                <p class="text-muted">Create your new password</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-floating mb-3">
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           placeholder="New Password">

                    <label for="password">New Password</label>
                </div>

                @error('password')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                <div class="form-floating mb-3">
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           id="password_confirmation"
                           placeholder="Confirm Password">

                    <label for="password_confirmation">
                        Confirm Password
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        Reset Password
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection