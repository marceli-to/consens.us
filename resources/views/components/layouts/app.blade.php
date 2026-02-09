@props(['wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>consens.us — Abstimmung</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="consens.us" />
    <link rel="manifest" href="/site.webmanifest" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans bg-cream text-ink min-h-screen">
    <div class="beta-ribbon" aria-label="Beta"></div>
    <div class="min-h-screen flex flex-col">
        <x-header />

        @if($wide)
            <main class="flex-1">
                {{ $slot }}
            </main>
        @else
            <main class="flex-1 py-12">
                <div class="max-w-2xl mx-auto px-6">
                    {{ $slot }}
                </div>
            </main>
        @endif

        <x-footer />
    </div>
    @livewireScripts
</body>
</html>
