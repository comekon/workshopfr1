<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body>

<div class="container-scroller">

    @auth

        @include('layouts.navbar')

        <div class="container-fluid page-body-wrapper">

            @include('layouts.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('layouts.footer')
            </div>

        </div>

    @else

        
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center justify-content-center">
                @yield('content')
            </div>
        </div>

    @endauth

</div>


{{-- JS GLOBAL --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

@stack('js-page')

</body>
</html>
