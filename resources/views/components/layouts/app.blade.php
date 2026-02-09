<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>consens.us — Abstimmung</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-sans bg-cream text-ink min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="border-b border-rule">
            <div class="max-w-2xl mx-auto px-6 h-14 flex items-center justify-between">
                <a href="/" class="font-serif text-lg font-bold">consens.us</a>
                <a href="{{ route('create') }}" class="text-[10px] tracking-[0.15em] uppercase text-ink-faint hover:text-ink transition-colors">Neuer Poll</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-12">
            <div class="max-w-2xl mx-auto px-6">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-rule">
            <div class="max-w-2xl mx-auto px-6 py-6 flex items-center justify-between text-[10px] tracking-wider uppercase text-ink-faint">
                <span>consens.us</span>
                <span>Made with <span class="text-red-600">♥</span> by Jarvis & <a href="https://marceli.to" target="_blank" class="hover:text-ink transition-colors">marceli.to</a></span>
            </div>
        </footer>
    </div>
    @livewireScripts
</body>
</html>
