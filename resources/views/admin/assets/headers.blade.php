<!DOCTYPE html>
<html lang="en"
      dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible"
              content="IE=edge">
        <meta name="viewport"
              content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>UCO-TMS</title>

        <!-- Safety net: never let the theme preloader trap the page.
             Placed before any stylesheet so slow CDNs cannot delay the timer. -->
        <script>
            setTimeout(function () {
                var preloader = document.querySelector('.preloader');
                if (!preloader) return;
                preloader.style.transition = 'opacity .4s ease';
                preloader.style.opacity = '0';
                preloader.style.pointerEvents = 'none';
                setTimeout(function () { preloader.style.display = 'none'; }, 450);
            }, 1000);
        </script>

        <!-- Prevent the demo from appearing in search engines -->
        <meta name="robots"
              content="noindex">

        <meta name="theme-color"
              content="#0B1F16">
        <link rel="icon"
              type="image/png"
              href="{{ asset('assets/images/uz-logo.png') }}">

        <script>
            /* Stored portraits can outlive their files — fall back to the UZ seal. */
            document.addEventListener('error', function (event) {
                var img = event.target;
                if (!img || img.tagName !== 'IMG' || img.dataset.fallbackApplied) return;
                if (!/\/storage\//.test(img.getAttribute('src') || '')) return;
                img.dataset.fallbackApplied = '1';
                img.src = '{{ asset('assets/images/uz-logo.png') }}';
                img.classList.add('img-fallback-seal');
            }, true);
        </script>

        <link rel="preconnect"
              href="https://fonts.googleapis.com">
        <link rel="preconnect"
              href="https://fonts.gstatic.com"
              crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&display=swap"
              rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7COswald:300,400,500,700%7CRoboto:400,500%7CExo+2:600&display=swap"
              rel="stylesheet">

        <!-- Perfect Scrollbar -->
        <link type="text/css"
              href="{{ asset('assets/vendor/perfect-scrollbar.css') }}"
              rel="stylesheet">

        <!-- Material Design Icons -->
        <link type="text/css"
              href="{{ asset('assets/css/material-icons.css') }}"
              rel="stylesheet">

        <!-- Font Awesome Icons -->
        <link type="text/css"
              href="{{ asset('assets/css/fontawesome.css') }}"
              rel="stylesheet">

        <!-- Preloader -->
        <link type="text/css"
              href="{{ asset('assets/vendor/spinkit.css') }}"
              rel="stylesheet">
        <link type="text/css"
              href="{{ asset('assets/css/preloader.css') }}"
              rel="stylesheet">

        <!-- App CSS -->
        <link type="text/css"
              href="{{ asset('assets/css/app.css') }}"
              rel="stylesheet">

        <!-- Vendor CSS -->
        <link rel="stylesheet"
              href="{{asset('assets/css/nestable.css')}}">
      
         <!-- Sweet Alert -->
        <link rel="stylesheet"
              href="{{ asset('assets/css/sweetalert.css') }}">

        <!-- Forest Whisper admin theme -->
        <link type="text/css"
              href="{{ asset('assets/css/forest-admin.css') }}"
              rel="stylesheet">

     @livewireStyles
  <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('reverb.apps.apps.0.key') }}',
        cluster: 'mt1',                       // required by the client, ignored by Reverb
        wsHost: window.location.hostname,
        wsPort: {{ env('REVERB_PORT', 8080) }},
        wssPort: {{ env('REVERB_PORT', 8080) }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
    });
</script>
    </head>