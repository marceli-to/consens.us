<div class="space-y-0" wire:poll.5s>
    {{-- Password Gate --}}
    @if($needsPassword)
        <section class="py-16 text-center">
            <h2 class="font-serif text-3xl font-bold tracking-tight mb-2">{{ $poll->title }}</h2>
            <p class="text-sm text-ink-muted mb-8">Dieser Poll ist passwortgeschützt.</p>
            <form wire:submit="authenticate" class="max-w-xs mx-auto space-y-4">
                <input type="password" wire:model="password"
                    class="w-full px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                    placeholder="Passwort">
                @if($passwordError)
                    <p class="text-sm text-accent">{{ $passwordError }}</p>
                @endif
                <button type="submit" class="w-full px-6 py-3 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                    Zugang
                </button>
            </form>
        </section>
    @else
        {{-- Poll Header --}}
        <section class="pb-8">
            <h2 class="font-serif text-3xl font-bold tracking-tight mb-1">{{ $poll->title }}</h2>
            @if($poll->description)
                <p class="text-sm text-ink-muted">{{ $poll->description }}</p>
            @endif
            @if($poll->is_closed)
                <div class="mt-3 inline-block px-3 py-1 text-[10px] uppercase tracking-[0.2em] border border-accent text-accent">Geschlossen</div>
            @endif
        </section>

        {{-- Voting Form --}}
        @if(!$poll->is_closed)
            @if(!$hasVoted)
                <section class="pb-12 border-b border-rule">
                    <div class="flex items-baseline justify-between mb-2">
                        <h3 class="text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted">Abstimmen</h3>
                        <span class="text-[10px] tracking-[0.2em] uppercase text-ink-faint">Mehrfachauswahl möglich</span>
                    </div>
                    <div class="h-px bg-ink mb-8"></div>

                    <form wire:submit="submitVote" class="space-y-8">
                        <div>
                            <label for="voterName" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Name</label>
                            <input type="text" id="voterName" wire:model="voterName"
                                class="w-full max-w-xs px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                                placeholder="Dein Name">
                            @error('voterName') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-3">Optionen</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($poll->options as $option)
                                    <label class="vote-option">
                                        <input type="checkbox" wire:model="selectedOptions" value="{{ $option->id }}">
                                        <div class="option-card">{{ $option->label }}</div>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedOptions') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                                Abstimmen
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </section>
            @else
                <section class="pb-12 border-b border-rule">
                    <div class="border-l-2 border-ink pl-6 py-2 flex items-center justify-between">
                        <div>
                            <p class="font-serif text-lg font-semibold text-ink">Danke, {{ $voterName }}.</p>
                            <p class="text-sm text-ink-muted mt-1">Deine Stimme wurde gespeichert.</p>
                        </div>
                        <button wire:click="editVote"
                            class="text-[10px] uppercase tracking-[0.2em] text-ink-faint hover:text-ink border-b border-ink-faint hover:border-ink pb-0.5 transition-colors cursor-pointer">
                            Bearbeiten
                        </button>
                    </div>
                </section>
            @endif
        @endif

        {{-- Results --}}
        <section class="pt-12">
            <div class="flex items-baseline justify-between mb-2">
                <h2 class="font-serif text-3xl font-bold tracking-tight">Ergebnisse</h2>
                <span class="text-[10px] tracking-[0.2em] uppercase text-ink-faint tabular-nums">
                    {{ $results['totalVoters'] }} {{ $results['totalVoters'] === 1 ? 'Stimme' : 'Stimmen' }}
                </span>
            </div>
            <div class="h-px bg-ink mb-10"></div>

            @if($results['totalVoters'] > 0)
                <div class="space-y-12">
                    <div>
                        <div class="space-y-5">
                            @foreach($results['options'] as $data)
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-ink">{{ $data['label'] }}</span>
                                        <span class="text-ink-muted tabular-nums">
                                            {{ $data['count'] }}&ensp;·&ensp;{{ $data['percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="h-0.5 bg-rule-light overflow-hidden">
                                        <div class="h-full bg-ink transition-all duration-700" style="width: {{ $data['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Voter Table --}}
                    <div>
                        <h3 class="text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-6">Alle Stimmen</h3>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-ink">
                                    <th class="text-left py-3 pr-4 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted">Name</th>
                                    <th class="text-left py-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted">Gewählt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['voters'] as $voter)
                                    <tr class="border-b border-rule-light">
                                        <td class="py-3 pr-4 text-ink font-medium">{{ $voter['name'] }}</td>
                                        <td class="py-3 text-ink-muted">
                                            {{ collect($voter['options'])->map(fn($id) => $poll->options->firstWhere('id', $id)?->label)->filter()->join(', ') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <p class="font-serif text-lg italic text-ink-muted">Noch keine Stimmen abgegeben.</p>
                    <p class="text-sm text-ink-faint mt-2">Sei der Erste.</p>
                </div>
            @endif
        </section>
    @endif
</div>
