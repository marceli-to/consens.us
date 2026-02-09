<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>consens.us — Group decisions, simplified.</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-cream text-ink">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-cream/90 backdrop-blur-sm">
        <div class="max-w-3xl mx-auto px-6 h-14 flex items-center justify-between">
            <span class="font-serif text-lg font-bold">consens.us</span>
            <a href="{{ route('create') }}" class="text-[11px] tracking-[0.15em] uppercase text-ink-muted hover:text-ink border-b border-ink-faint hover:border-ink pb-0.5 transition-colors">
                Poll erstellen →
            </a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="min-h-screen flex items-center justify-center pt-14">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h1 class="font-serif text-6xl sm:text-8xl font-bold tracking-tight leading-[0.9] mb-6">
                consens.us
            </h1>
            <div class="flex items-center justify-center gap-6 mb-8">
                <span class="h-px w-16 bg-rule"></span>
                <span class="text-[10px] tracking-[0.25em] uppercase text-ink-faint">Group decisions, simplified</span>
                <span class="h-px w-16 bg-rule"></span>
            </div>
            <p class="text-lg sm:text-xl text-ink-muted max-w-lg mx-auto leading-relaxed font-light">
                Eine Entscheidung treffen, die allen passt — ohne endlose Gruppenchats.
            </p>
            <div class="mt-12">
                <a href="#how" class="text-sm text-ink-muted hover:text-ink transition-colors">
                    Mehr erfahren ↓
                </a>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how" class="py-24 sm:py-32">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-16">
                <p class="text-[10px] tracking-[0.3em] uppercase text-ink-faint mb-3">So funktioniert's</p>
                <h2 class="font-serif text-4xl sm:text-5xl font-bold tracking-tight">Drei Schritte<br>zum Konsens.</h2>
            </div>

            <div class="space-y-0">
                <!-- Step 1 -->
                <div class="grid sm:grid-cols-[80px_1fr] gap-4 py-10 border-t border-rule">
                    <span class="font-serif text-5xl font-bold text-rule">01</span>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Abstimmen</h3>
                        <p class="text-ink-muted leading-relaxed">Jeder wählt, was am besten passt. Mehrfachauswahl möglich — alles ankreuzen, was geht.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="grid sm:grid-cols-[80px_1fr] gap-4 py-10 border-t border-rule">
                    <span class="font-serif text-5xl font-bold text-rule">02</span>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Ergebnisse</h3>
                        <p class="text-ink-muted leading-relaxed">Die Resultate werden live angezeigt. Auf einen Blick sehen, was die meisten Stimmen hat — kein Zusammenzählen, kein Hin und Her.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="grid sm:grid-cols-[80px_1fr] gap-4 py-10 border-t border-rule">
                    <span class="font-serif text-5xl font-bold text-rule">03</span>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Entscheiden</h3>
                        <p class="text-ink-muted leading-relaxed">Wenn alle abgestimmt haben, steht das Ergebnis fest. Demokratisch, transparent, ohne Drama.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-24 sm:py-32 border-t border-rule">
        <div class="max-w-3xl mx-auto px-6">
            <div class="mb-16">
                <p class="text-[10px] tracking-[0.3em] uppercase text-ink-faint mb-3">Features</p>
                <h2 class="font-serif text-4xl sm:text-5xl font-bold tracking-tight">Einfach gehalten.<br>Bewusst.</h2>
            </div>

            <div class="grid sm:grid-cols-2 gap-x-16 gap-y-10">
                <div>
                    <h4 class="font-semibold mb-2">Kein Account nötig</h4>
                    <p class="text-sm text-ink-muted leading-relaxed">Link teilen, Passwort eingeben, abstimmen. Fertig. Keine Registrierung, kein Login-Chaos.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Live Ergebnisse</h4>
                    <p class="text-sm text-ink-muted leading-relaxed">Resultate aktualisieren sich automatisch. Alle sehen den gleichen Stand — in Echtzeit.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Stimme ändern</h4>
                    <p class="text-sm text-ink-muted leading-relaxed">Meinungsänderung? Kein Problem. Stimmen lassen sich jederzeit anpassen.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Mobilfreundlich</h4>
                    <p class="text-sm text-ink-muted leading-relaxed">Funktioniert auf jedem Gerät. Abstimmen vom Sofa, der Bahn oder dem Büro.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 sm:py-32 border-t border-rule">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="font-serif text-4xl sm:text-5xl font-bold tracking-tight mb-6">Bereit?</h2>
            <p class="text-lg text-ink-muted mb-10 max-w-md mx-auto">Stimme jetzt ab und hilf mit, die beste Entscheidung für alle zu treffen.</p>
            <a href="{{ route('create') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors tracking-wide uppercase">
                Poll erstellen
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-rule">
        <div class="max-w-3xl mx-auto px-6 py-6 flex items-center justify-between text-[10px] tracking-wider uppercase text-ink-faint">
            <span>consens.us</span>
            <span>Made with <span class="text-red-600">♥</span> by Jarvis & <a href="https://marceli.to" target="_blank" class="hover:text-ink transition-colors">marceli.to</a></span>
        </div>
    </footer>

</body>
</html>
