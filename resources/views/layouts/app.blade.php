<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AQI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite('resources/css/app.pcss')

    <script>
        const shell = require('electron').shell;
    </script>
</head>

<body class="antialiased flex flex-col justify-between content-between h-screen overflow-hidden">
    <header class="w-full">
        @yield('header')
    </header>

    <main class="flex flex-col justify-start overflow-y-scroll">
        @yield('content')
    </main>
</body>
</html>
