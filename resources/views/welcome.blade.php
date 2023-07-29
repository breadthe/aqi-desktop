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
    </head>
    <body class="antialiased flex flex-col h-screen overflow-hidden">
        <header class="w-full">
            <livewire:api-key />
        </header>

        <main class="flex flex-col justify-start overflow-y-scroll">
            <livewire:fetch-observation />

            <livewire:show-observation />
        </main>
    </body>
</html>
