<div>
    <section class="pb-12">
        <div class="flex items-baseline justify-between mb-8">
            <h2 class="font-serif text-3xl font-bold tracking-tight">Umfrage verwalten</h2>
            <span class="text-micro tracking-[0.2em] uppercase text-ink-faint">Admin</span>
        </div>

        {{-- URLs --}}
        <div class="space-y-4 mb-12">
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Öffentlicher Link</label>
                <div class="flex gap-2">
                    <input type="text" value="{{ $publicUrl }}" readonly
                        class="flex-1 px-4 py-3 text-base sm:text-sm border border-rule bg-cream-dark text-ink-muted focus:outline-none">
                    <button onclick="navigator.clipboard.writeText('{{ $publicUrl }}')"
                        class="px-4 py-3 text-sm bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                        Kopieren
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Admin Link <span class="text-ink-faint">(nur für dich)</span></label>
                <div class="flex gap-2">
                    <input type="text" value="{{ $editUrl }}" readonly
                        class="flex-1 px-4 py-3 text-base sm:text-sm border border-rule bg-cream-dark text-ink-muted focus:outline-none">
                    <button onclick="navigator.clipboard.writeText('{{ $editUrl }}')"
                        class="px-4 py-3 text-sm bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                        Kopieren
                    </button>
                </div>
            </div>
        </div>

        {{-- Edit Details --}}
        <div class="space-y-6 mb-12 pb-12 border-b border-rule">
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Titel</label>
                <input type="text" wire:model="title"
                    class="w-full px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink focus:outline-none focus:border-ink transition-colors">
            </div>
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Beschreibung</label>
                <textarea wire:model="description" rows="3"
                    class="w-full px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink focus:outline-none focus:border-ink transition-colors resize-none"></textarea>
            </div>
            <button wire:click="saveDetails"
                class="px-6 py-3 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                Speichern
            </button>
        </div>

        {{-- Voting Mode (read-only) --}}
        <div class="mb-12 pb-12 border-b border-rule">
            <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Abstimmungsmodus</label>
            <div class="px-4 py-3 text-sm border border-rule bg-cream-dark text-ink-muted">
                @if($poll->voting_mode === 'checkbox')
                    Mehrfachauswahl
                @elseif($poll->voting_mode === 'radio')
                    Einzelauswahl
                @elseif($poll->voting_mode === 'yesnomaybe')
                    Ja · Nein · Vielleicht
                @endif
            </div>
            <p class="mt-1 text-micro text-ink-faint">Der Abstimmungsmodus kann nach Erstellung nicht geändert werden.</p>
        </div>

        {{-- Options --}}
        <div class="mb-12 pb-12 border-b border-rule">
            <h3 class="text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-4">Optionen</h3>
            <div class="space-y-2 mb-4">
                @foreach($options as $option)
                    <div class="flex items-center justify-between py-2 px-4 border border-rule">
                        <span class="text-sm">{{ $option['label'] }}</span>
                        <button wire:click="removeOption({{ $option['id'] }})"
                            class="text-micro uppercase tracking-[0.2em] text-ink-faint hover:text-accent transition-colors cursor-pointer">
                            Entfernen
                        </button>
                    </div>
                @endforeach
            </div>
            <div class="flex gap-2">
                <input type="text" wire:model="newOption" wire:keydown.enter="addOption"
                    class="flex-1 px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                    placeholder="Neue Option...">
                <button wire:click="addOption"
                    class="px-4 py-3 text-sm bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                    Hinzufügen
                </button>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-4">
            <button wire:click="toggleComments"
                class="px-6 py-3 text-sm font-medium border border-ink text-ink hover:bg-ink hover:text-cream transition-colors cursor-pointer tracking-wide uppercase">
                {{ $allowComments ? 'Kommentare deaktivieren' : 'Kommentare aktivieren' }}
            </button>

            <button wire:click="toggleClose"
                class="px-6 py-3 text-sm font-medium border border-ink text-ink hover:bg-ink hover:text-cream transition-colors cursor-pointer tracking-wide uppercase">
                {{ $poll->is_closed ? 'Umfrage wieder öffnen' : 'Umfrage schliessen' }}
            </button>

            @if(!$showDeleteConfirm)
                <button wire:click="$set('showDeleteConfirm', true)"
                    class="block text-micro uppercase tracking-[0.2em] text-ink-faint hover:text-accent transition-colors cursor-pointer mt-6">
                    Umfrage löschen
                </button>
            @else
                <div class="mt-6 p-4 border border-accent">
                    <p class="text-sm text-accent mb-3">Bist du sicher? Das kann nicht rückgängig gemacht werden.</p>
                    <div class="flex gap-2">
                        <button wire:click="deletePoll"
                            class="px-4 py-2 text-sm bg-accent text-cream hover:opacity-80 transition-colors cursor-pointer tracking-wide uppercase">
                            Ja, löschen
                        </button>
                        <button wire:click="$set('showDeleteConfirm', false)"
                            class="px-4 py-2 text-sm border border-rule text-ink-muted hover:text-ink transition-colors cursor-pointer tracking-wide uppercase">
                            Abbrechen
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
