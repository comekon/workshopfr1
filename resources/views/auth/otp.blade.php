@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('content')
<div class="row flex-grow">
  <div class="col-lg-4 mx-auto">
    <div class="auth-form-light p-5">

      <h4>Masukkan OTP</h4>

      <form method="POST" action="/otp">
        @csrf

        <div class="form-group">
          <input type="text"
                 name="otp"
                 maxlength="6"
                 class="form-control form-control-lg text-center"
                 placeholder="6 Digit OTP"
                 required>
        </div>

        <button class="btn btn-gradient-primary w-100">
          Verifikasi
        </button>

      </form>

    </div>
  </div>
</div>
@endsection
