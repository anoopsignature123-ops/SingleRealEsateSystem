@extends('auth.app')

@section('content')
    <div class="d-flex vh-100">

        {{-- LEFT SIDE --}}
        <div class="d-none d-md-flex col-md-6 position-relative p-0">

            <div class="w-100 h-100"
                style="background: url('{{ asset('assets/images/login_b.jpg') }}') center/cover no-repeat;">
            </div>

            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);">
            </div>

            <div class="position-absolute top-50 start-50 translate-middle text-white text-center px-4">

                <h1 class="fw-bold display-6">

                    Associate Panel

                </h1>

                <p class="mt-3">

                    Login to access your associate dashboard

                </p>

            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center bg-light">

            <div class="w-100 px-4" style="max-width: 380px;">

                <div class="text-center mb-4">

                    <img src="{{ asset('assets/images/avatar.png') }}" alt="Logo" style="height: 60px;">

                </div>

                <div class="text-center mb-4">

                    <h3 class="fw-bold text-success">

                        Associate Login

                    </h3>

                    <p class="text-muted small">

                        Sign in to continue

                    </p>

                </div>

                <form method="POST" action="{{ route('associate-panel.login.submit') }}">
                    @csrf

                    {{-- ASSOCIATE ID --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="associate_id" value="{{ old('associate_id') }}"
                            class="form-control border-success @error('associate_id') is-invalid @enderror"
                            id="associate_id" placeholder="Associate ID">
                        <label for="associate_id">Associate ID</label>

                        {{-- FIXED: Bootstrap class added --}}
                        @error('associate_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                            class="form-control border-success @error('password') is-invalid @enderror" id="password"
                            placeholder="Password">
                        <label for="password">Password</label>

                        {{-- FIXED: Bootstrap class added --}}
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- REMEMBER --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    {{-- BUTTON --}}
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
