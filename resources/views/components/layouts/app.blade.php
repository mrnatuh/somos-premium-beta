<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

        @livewireStyles
        @livewireScripts

        @vite('resources/css/app.css')
    </head>
    <body id="app" class="font-sans antialiased h-screen bg-white relative">
        <div id="app" class="w-full h-screen">
            <div class="flex w-full h-full">
                <x-nav />

                <main class="w-full h-full relative overflow-y-scroll overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @vite('resources/js/app.js')
    </body>
</html>
