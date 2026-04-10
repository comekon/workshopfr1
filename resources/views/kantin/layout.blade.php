<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kantin Online')</title>

    {{-- Style Global (same as admin template) --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .kantin-navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 12px 0;
        }
        .kantin-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #6c63ff;
            text-decoration: none;
        }
        .kantin-brand i { margin-right: 8px; }
        .kantin-body {
            padding: 30px 0;
            min-height: calc(100vh - 70px);
        }
        .kantin-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .kantin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
        }
        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
        }
        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #5a6fd6, #6a4299);
            color: #fff;
        }
        .vendor-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .vendor-card:hover, .vendor-card.active {
            border-color: #6c63ff;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.25);
        }
        .vendor-card.active {
            background: linear-gradient(135deg, #667eea10, #764ba210);
        }
        .menu-card {
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .menu-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .menu-card .menu-img {
            height: 140px;
            object-fit: cover;
            width: 100%;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
        }
        .menu-card .menu-img-placeholder {
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc);
            border-radius: 12px 12px 0 0;
            font-size: 3rem;
            color: rgba(255,255,255,0.8);
        }
        .badge-qty {
            width: 28px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            border-radius: 50%;
            font-size: 0.8rem;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        .step-dot {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem;
            background: rgba(255,255,255,0.25);
            color: #fff;
            transition: all 0.3s;
        }
        .step-dot.active {
            background: #fff;
            color: #6c63ff;
            box-shadow: 0 4px 15px rgba(255,255,255,0.4);
        }
        .step-dot.done {
            background: #4caf50;
            color: #fff;
        }
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .kantin-footer {
            text-align: center;
            padding: 20px;
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }
    </style>

    @stack('style-page')
</head>
<body>

    {{-- Navbar --}}
    <nav class="kantin-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('kantin.index') }}" class="kantin-brand">
                <i class="mdi mdi-food"></i>Kantin Online
            </a>
            <div>
                <span class="text-muted small">
                    <i class="mdi mdi-cart"></i>
                    <span id="nav-cart-count">0</span> item
                </span>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="kantin-body">
        <div class="container">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <div class="kantin-footer">
        &copy; {{ date('Y') }} Kantin Online — Workshop Framework
    </div>

    {{-- JS --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('js-page')

</body>
</html>
