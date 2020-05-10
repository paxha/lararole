<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __("Lararole") }}</title>

    <!-- icon -->
    <link rel="icon" href="{{ asset('vendor/lararole/lararole.ico') }}">

    <!-- Scripts -->
    <script src="{{ asset('vendor/lararole/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('vendor/lararole/css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
<div id="app">
</div>
</body>
</html>
