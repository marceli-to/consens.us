<header>
    <div class="max-w-2xl mx-auto px-6 h-14 flex items-center justify-between">
        <a href="/" class="font-serif text-lg font-bold">consens.us</a>
        @if(request()->routeIs('create'))
            <a href="/" class="text-[10px] tracking-[0.15em] uppercase text-ink-faint hover:text-ink transition-colors">Abbrechen</a>
        @elseif(request()->routeIs('home'))
            <a href="{{ route('create') }}" class="group text-[10px] tracking-[0.15em] uppercase text-ink-faint hover:text-ink transition-colors">
                <span class="border-b border-transparent group-hover:border-ink pb-0.5 transition-colors">Umfrage erstellen</span> →
            </a>
        @else
            <a href="{{ route('create') }}" class="text-[10px] tracking-[0.15em] uppercase text-ink-faint hover:text-ink transition-colors">Neue Umfrage</a>
        @endif
    </div>
</header>
