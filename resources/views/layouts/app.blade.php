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


<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
{{-- JS GLOBAL --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

@stack('js-page')

<script>
function submitForm(formId, btnId) {
    let form = document.getElementById(formId);
    let btn = document.getElementById(btnId);
    
    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    let originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
    btn.disabled = true;
    
    setTimeout(() => {
        form.submit();
    }, 500); // little delay to see the spinner
}
</script>

</body>
</html>
