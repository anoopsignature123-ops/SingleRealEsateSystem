@extends('auth.app')

@section('content')
<div class="d-flex vh-100">

    <div class="col-12 d-flex align-items-center justify-content-center bg-light">
        <div class="w-100 px-4" style="max-width: 400px;">

            <div class="text-center mb-4">
                <h3 class="fw-bold text-success">Forgot Password</h3>
                <p class="text-muted">Enter your email address</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           placeholder="Email">

                    <label for="email">Email Address</label>
                </div>

                @error('email')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-success">
                        Send Reset Link
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        Back to Login
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection