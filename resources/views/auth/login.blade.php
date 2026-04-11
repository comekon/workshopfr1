@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="row flex-grow">
  <div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">

      <div class="brand-logo text-center mb-4">
        <img src="{{ asset('assets/images/logo.svg') }}">
      </div>

      <h4>Hello! let's get started</h4>
      <h6 class="font-weight-light">Sign in to continue.</h6>

      <form method="POST" action="{{ route('login') }}" class="pt-3">
            @csrf

            {{-- EMAIL --}}
            <div class="form-group">
                <input type="email"
                    name="email"
                    class="form-control form-control-lg"
                    placeholder="Email"
                    required>
            </div>

            {{-- PASSWORD --}}
            <div class="form-group">
                <input type="password"
                    name="password"
                    class="form-control form-control-lg"
                    placeholder="Password"
                    required>
            </div>

            {{-- BUTTON LOGIN --}}
            <div class="mt-3 d-grid gap-2">
                <button type="submit"
                        class="btn btn-gradient-primary btn-lg w-100">
                    SIGN IN
                </button>
            </div>

            {{-- REMEMBER + FORGOT --}}
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <label class="form-check-label text-muted">
                        Keep me signed in
                    </label>
                </div>

                <a href="{{ route('password.request') }}"
                class="auth-link text-primary">
                Forgot password?
                </a>
            </div>

        </form> {{-- 🔥 FORM DITUTUP DI SINI --}}

        {{-- LOGIN GOOGLE (DI LUAR FORM) --}}
        <div class="mb-2 d-grid gap-2">
            <a href="{{ url('/auth/google') }}"
            class="btn btn-danger w-100">
                <i class="mdi mdi-google me-2"></i>
                Login dengan Google
            </a>
        </div>

        {{-- KANTIN ONLINE --}}
        <div class="mb-2 d-grid gap-2">
            <a href="{{ route('kantin.index') }}"
               class="btn btn-success w-100">
                <i class="mdi mdi-store me-2"></i>
                Kantin Online
            </a>
        </div>

        {{-- REGISTER --}}
        <div class="text-center mt-4 font-weight-light">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary">
                Create
            </a>
</div>
    </div>
  </div>
</div>
@endsection
