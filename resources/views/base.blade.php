<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kyle Boehlen Portfolio') }}</title>

        <!-- Scripts -->
        @if(config('app.env') == 'local')
            <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        @else
            <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
        @endif
        
        <!-- Styles -->
        @if(config('app.env') == 'local')
            <link href="@isset($stylesheet) {{ asset("css/$stylesheet.css") }} @else {{ asset('css/app.css') }} @endisset" rel="stylesheet">
        @else
            <link href="@isset($stylesheet) {{ mix("/css/$stylesheet.css") }} @else {{ mix('/css/app.css') }} @endisset" rel="stylesheet">
        @endif

        <!-- Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        {{-- Child push --}}
        @stack('head')

        {{-- HTML5 Shiv --}}
        <!--[if lt IE 9]>
            <script src="bower_components/html5shiv/dist/html5shiv.js"></script>
        <![endif]-->
    </head>

    <body>
        @yield('body')
    </body>
</html>
