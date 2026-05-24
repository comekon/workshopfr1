@extends('layouts.antrian-public')

@section('title', 'Daftar Antrian')

@section('style-page')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Nunito:wght@400;600;700;800&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Nunito', sans-serif;
        background: #0a0e1a;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .guest-wrapper {
        width: 100%;
        max-width: 440px;
        padding: 20px;
        animation: cardEntry 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes cardEntry {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .guest-card {
        background: linear-gradient(160deg, #151b33 0%, #0f1629 100%);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #1e2a4a;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    .guest-header {
        background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
        padding: 28px 28px 24px;
        text-align: center;
        position: relative;
    }

    .guest-header::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 24px;
        right: 24px;
        height: 24px;
        background: #151b33;
        border-radius: 12px 12px 0 0;
    }

    .guest-header i {
        font-size: 40px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 8px;
    }

    .guest-header h2 {
        font-family: 'Nunito', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        margin: 0;
    }

    .guest-header p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 6px;
    }

    .guest-body {
        padding: 36px 28px 28px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #8892b0;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 18px;
        background: #0d1222;
        border: 1px solid #1e2a4a;
        border-radius: 10px;
        color: #c5cee0;
        font-size: 15px;
        font-family: 'Nunito', sans-serif;
        font-weight: 600;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-group input::placeholder {
        color: #3d4663;
        font-weight: 600;
    }

    .form-group input:focus {
        border-color: #e63946;
        box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
    }

    .form-error {
        font-size: 13px;
        color: #e63946;
        margin-top: 8px;
        font-weight: 600;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Nunito', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: 0.5px;
    }

    .btn-submit:hover {
        opacity: 0.9;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .guest-footer {
        padding: 0 28px 24px;
        text-align: center;
    }

    .guest-footer p {
        font-size: 11px;
        color: #3d4663;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')
<div class="guest-wrapper">
    <div class="guest-card">
        <div class="guest-header">
            <i class="mdi mdi-ticket-confirmation-outline"></i>
            <h2>Ambil Antrian</h2>
            <p>Masukkan nama Anda untuk mendaftar</p>
        </div>

        <div class="guest-body">
            <form method="POST" action="{{ route('antrian.store') }}">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" placeholder="Masukkan nama Anda" required autofocus>
                    @error('nama')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-submit">
                    <i class="mdi mdi-ticket-outline"></i>
                    Ambil Nomor Antrian
                </button>
            </form>
        </div>

        <div class="guest-footer">
            <p>Sistem Antrian Digital</p>
        </div>
    </div>
</div>
@endsection
