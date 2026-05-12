@extends('auth.app')

@section('content')
    <div class="d-flex vh-100">

        <!-- LEFT SIDE -->
        <div class="d-none d-md-flex col-md-6 position-relative p-0">
            <div class="w-100 h-100"
                style="background: url('{{ asset('assets/images/login_b.jpg') }}') center/cover no-repeat;">
            </div>

            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);">
            </div>

            <div class="position-absolute top-50 start-50 translate-middle text-white text-center px-4">
                <h1 class="fw-bold display-6">Real Estate Management Software</h1>
                <p class="mt-3">Manage your admin panel efficiently and securely</p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-100 px-4" style="max-width: 380px;">

                <div class="text-center mb-4">
                    <img src="{{ asset('assets/images/avatar.png') }}" alt="Logo" style="height: 60px;">
                </div>

                <div class="text-center mb-4">
                    <h3 class="fw-bold text-success">Admin Login</h3>
                    <p class="text-muted small">Sign in to continue</p>
                </div>


                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="form-floating mb-1">
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control border-success @error('email') is-invalid @enderror" id="email"
                            placeholder="name@example.com">

                        <label for="email">Email address</label>
                    </div>

                    @error('email')
                        <div class="text-danger small mb-2">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- PASSWORD -->
                    <div class="form-floating mb-1">
                        <input type="password" name="password"
                            class="form-control border-success @error('password') is-invalid @enderror" id="password"
                            placeholder="Password">

                        <label for="password">Password</label>
                    </div>

                    @error('password')
                        <div class="text-danger small mb-2">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- REMEMBER + FORGOT -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-success text-decoration-none">
                            Forgot Password?
                        </a>
                    </div>
                    <!-- BUTTON -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success py-2 fw-semibold shadow-sm">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection