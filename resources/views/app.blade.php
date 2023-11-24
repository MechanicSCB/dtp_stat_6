<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/balloon.css') }}">

        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('css/icons.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Merriweather&amp;display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset('js/balloon.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/getFilterCacheKey.js') }}" type="text/javascript"></script>

        <!-- Yandex Map -->
        <script src="https://api-maps.yandex.ru/2.1/?apikey=9a2b8ed8-161a-4324-882c-c76cfdf2357c&suggest_apikey=f2070962-bdcd-4357-b3f5-4dc62f03879e&lang=ru_RU" type="text/javascript"></script>

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-['Roboto'] antialiased">
        @inertia
    </body>
</html>
