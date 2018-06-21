<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="@yield('style')">
    <div id="app">
        <main class="container container-small">
            <h1 class="site-title text-center">
                <a href="/">
                    <span class="fa-layers" style="margin-right: -5px">
                        <i class="fa fa-circle"></i>
                        <span class="fa-layers-text" data-fa-transform="shrink-11.5 rotate--30" style="font-weight:900">01010</span>
                    </span>
                    <span>Disket</span>
                </a>
            </h1>
            @yield('content')
        </main>
    </div>
</body>
</html>
