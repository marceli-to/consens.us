<div>
    <section class="pb-12">
        <div class="mb-8">
            <h2 class="font-serif text-3xl font-bold tracking-tight">Neue Umfrage</h2>
        </div>

        <form wire:submit="submit" class="space-y-10">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Titel</label>
                <input type="text" id="title" wire:model="title"
                    class="w-full px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                    placeholder="Worum geht es?">
                @error('title') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Beschreibung <span class="text-ink-faint">(optional)</span></label>
                <textarea id="description" wire:model="description" rows="3"
                    class="w-full px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors resize-none"
                    placeholder="Zusätzliche Infos..."></textarea>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-3">Typ</label>
                <div class="flex flex-wrap gap-2">
                    <label class="vote-option">
                        <input type="radio" wire:model.live="type" value="freetext" class="sr-only">
                        <div class="option-card {{ $type === 'freetext' ? '!border-ink !bg-ink !text-cream' : '' }}">Freitext</div>
                    </label>
                    <label class="vote-option">
                        <input type="radio" wire:model.live="type" value="date" class="sr-only">
                        <div class="option-card {{ $type === 'date' ? '!border-ink !bg-ink !text-cream' : '' }}">Datum</div>
                    </label>
                </div>
            </div>

            {{-- Voting Mode --}}
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-3">Abstimmungsmodus</label>
                <div class="flex flex-wrap gap-2">
                    <label class="vote-option">
                        <input type="radio" wire:model.live="votingMode" value="checkbox" class="sr-only">
                        <div class="option-card {{ $votingMode === 'checkbox' ? '!border-ink !bg-ink !text-cream' : '' }}">Mehrfachauswahl</div>
                    </label>
                    <label class="vote-option">
                        <input type="radio" wire:model.live="votingMode" value="radio" class="sr-only">
                        <div class="option-card {{ $votingMode === 'radio' ? '!border-ink !bg-ink !text-cream' : '' }}">Einzelauswahl</div>
                    </label>
                    <label class="vote-option">
                        <input type="radio" wire:model.live="votingMode" value="yesnomaybe" class="sr-only">
                        <div class="option-card {{ $votingMode === 'yesnomaybe' ? '!border-ink !bg-ink !text-cream' : '' }}">Ja · Nein · Vielleicht</div>
                    </label>
                </div>
                <p class="mt-2 text-micro text-ink-faint">
                    @if($votingMode === 'checkbox')
                        Teilnehmer können mehrere Optionen wählen.
                    @elseif($votingMode === 'radio')
                        Teilnehmer können genau eine Option wählen.
                    @else
                        Teilnehmer bewerten jede Option mit Ja, Nein oder Vielleicht.
                    @endif
                </p>
            </div>

            {{-- Options --}}
            <div>
                <label class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-3">Optionen</label>
                <div class="space-y-3">
                    @foreach($options as $i => $option)
                        <div class="flex gap-2">
                            @if($type === 'date')
                                <div wire:ignore class="flex-1"
                                    x-data
                                    x-init="
                                        flatpickr($refs.input_{{ $i }}, {
                                            dateFormat: 'Y-m-d',
                                            altInput: true,
                                            altFormat: 'j. F Y',
                                            defaultDate: $wire.options[{{ $i }}] || null,
                                            onChange: function(selectedDates, dateStr) {
                                                $wire.set('options.{{ $i }}', dateStr);
                                            }
                                        })
                                    ">
                                    <input x-ref="input_{{ $i }}" type="text"
                                        class="w-full px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors cursor-pointer"
                                        placeholder="Datum wählen"
                                        readonly>
                                </div>
                            @else
                                <input type="text" wire:model="options.{{ $i }}"
                                    class="flex-1 px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                                    placeholder="Option {{ $i + 1 }}">
                            @endif
                            @if(count($options) > 2)
                                <button type="button" wire:click="removeOption({{ $i }})"
                                    class="px-3 py-3 text-sm border border-rule text-ink-muted hover:text-accent hover:border-accent transition-colors cursor-pointer">✕</button>
                            @endif
                        </div>
                    @endforeach
                    @error('options.*') <p class="text-sm text-accent">{{ $message }}</p> @enderror
                </div>
                <button type="button" wire:click="addOption"
                    class="group mt-3 text-micro uppercase tracking-[0.2em] text-ink-faint hover:text-ink transition-colors cursor-pointer">
                    + <span class="border-b border-transparent group-hover:border-ink pb-0.5 transition-colors">Option hinzufügen</span>
                </button>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-micro font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Passwort <span class="text-ink-faint">(optional)</span></label>
                <input type="text" id="password" wire:model="password"
                    class="w-full max-w-xs px-4 py-3 text-base sm:text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                    placeholder="Zugangsschutz">
                <p class="mt-1 text-micro text-ink-faint">Wenn gesetzt, müssen Teilnehmer das Passwort eingeben.</p>
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                    Umfrage erstellen
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </section>
</div>
