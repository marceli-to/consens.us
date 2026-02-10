<div class="space-y-0" wire:poll.5s>
    {{-- Password Gate --}}
    @if($needsPassword)
        <section class="py-16 text-center">
            <h2 class="font-serif text-3xl font-bold tracking-tight mb-2">{{ $poll->title }}</h2>
            <p class="text-sm text-ink-muted mb-8">Diese Umfrage ist passwortgeschützt.</p>
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
                    <div class="mb-8">
                        <h3 class="text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted">Abstimmen</h3>
                    </div>

                    <form wire:submit="submitVote" class="space-y-8">
                        <div>
                            <label for="voterName" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Name</label>
                            <input type="text" id="voterName" wire:model="voterName"
                                class="w-full max-w-xs px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                                placeholder="Dein Name">
                            @error('voterName') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-3">Optionen
                                @if($poll->isSingleChoice())
                                    <span class="text-ink-faint">(Einzelauswahl)</span>
                                @elseif($poll->isYesNoMaybe())
                                    <span class="text-ink-faint">(Ja · Nein · Vielleicht)</span>
                                @else
                                    <span class="text-ink-faint">(Mehrfachauswahl)</span>
                                @endif
                            </label>

                            @if($poll->isYesNoMaybe())
                                {{-- Yes/No/Maybe voting --}}
                                <div class="space-y-3">
                                    @foreach($poll->options as $option)
                                        <div class="ynm-row flex items-center gap-3">
                                            <span class="text-sm text-ink flex-1 min-w-0">{{ $option->label }}</span>
                                            <div class="flex gap-1 shrink-0">
                                                <label class="ynm-option">
                                                    <input type="radio" wire:model="optionValues.{{ $option->id }}" value="yes" class="sr-only">
                                                    <div class="ynm-card ynm-yes {{ ($optionValues[(string)$option->id] ?? '') === 'yes' ? 'ynm-active' : '' }}">Ja</div>
                                                </label>
                                                <label class="ynm-option">
                                                    <input type="radio" wire:model="optionValues.{{ $option->id }}" value="maybe" class="sr-only">
                                                    <div class="ynm-card ynm-maybe {{ ($optionValues[(string)$option->id] ?? '') === 'maybe' ? 'ynm-active' : '' }}">Vielleicht</div>
                                                </label>
                                                <label class="ynm-option">
                                                    <input type="radio" wire:model="optionValues.{{ $option->id }}" value="no" class="sr-only">
                                                    <div class="ynm-card ynm-no {{ ($optionValues[(string)$option->id] ?? '') === 'no' ? 'ynm-active' : '' }}">Nein</div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('optionValues') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                                @error('optionValues.*') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror

                            @elseif($poll->isSingleChoice())
                                {{-- Single choice (radio) voting --}}
                                <div class="space-y-2">
                                    @foreach($poll->options as $option)
                                        <label class="vote-option-row">
                                            <input type="radio" wire:model="selectedOption" value="{{ $option->id }}">
                                            <span class="vote-option-label">{{ $option->label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedOption') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror

                            @else
                                {{-- Multi choice (checkbox) voting --}}
                                <div class="space-y-2">
                                    @foreach($poll->options as $option)
                                        <label class="vote-option-row">
                                            <input type="checkbox" wire:model="selectedOptions" value="{{ $option->id }}">
                                            <span class="vote-option-label">{{ $option->label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedOptions') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                            @endif
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
            <div class="flex items-baseline justify-between pb-4 mb-10 border-b border-ink">
                <h2 class="font-serif text-3xl font-bold tracking-tight">Ergebnisse</h2>
                <span class="text-[10px] tracking-[0.2em] uppercase text-ink-faint tabular-nums">
                    {{ $results['totalVoters'] }} {{ $results['totalVoters'] === 1 ? 'Stimme' : 'Stimmen' }}
                </span>
            </div>

            @if($results['totalVoters'] > 0)
                @if($poll->isYesNoMaybe())
                    {{-- YNM Results: bar + voter names per option --}}
                    <div class="space-y-8">
                        @foreach($results['options'] as $data)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-ink">{{ $data['label'] }}</span>
                                    <span class="text-ink-muted tabular-nums text-xs">
                                        <span class="text-emerald-600 font-semibold">{{ $data['yes'] }}</span>
                                        &ensp;
                                        <span class="text-amber-500 font-semibold">{{ $data['maybe'] }}</span>
                                        &ensp;
                                        <span class="text-red-500 font-semibold">{{ $data['no'] }}</span>
                                    </span>
                                </div>
                                <div class="h-1.5 bg-rule-light overflow-hidden flex mb-2">
                                    @if($data['yesPercent'] > 0)
                                        <div class="h-full bg-emerald-600 transition-all duration-700" style="width: {{ $data['yesPercent'] }}%"></div>
                                    @endif
                                    @if($data['maybePercent'] > 0)
                                        <div class="h-full bg-amber-400 transition-all duration-700" style="width: {{ $data['maybePercent'] }}%"></div>
                                    @endif
                                    @if($data['noPercent'] > 0)
                                        <div class="h-full bg-red-400 transition-all duration-700" style="width: {{ $data['noPercent'] }}%"></div>
                                    @endif
                                </div>
                                <div class="text-xs text-ink-muted space-y-0.5">
                                    @if(count($data['yesVoters']) > 0)
                                        <p><span class="text-emerald-600">Ja:</span> {{ implode(', ', $data['yesVoters']) }}</p>
                                    @endif
                                    @if(count($data['maybeVoters']) > 0)
                                        <p><span class="text-amber-500">Vielleicht:</span> {{ implode(', ', $data['maybeVoters']) }}</p>
                                    @endif
                                    @if(count($data['noVoters']) > 0)
                                        <p><span class="text-red-500">Nein:</span> {{ implode(', ', $data['noVoters']) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Checkbox / Radio Results: bar + voter names per option --}}
                    <div class="space-y-8">
                        @foreach($results['options'] as $data)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-ink">{{ $data['label'] }}</span>
                                    <span class="text-ink font-semibold tabular-nums">
                                        {{ $data['count'] }}
                                    </span>
                                </div>
                                <div class="h-0.5 bg-rule-light overflow-hidden mb-2">
                                    <div class="h-full bg-ink transition-all duration-700" style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                                @if(count($data['voterNames']) > 0)
                                    <p class="text-xs text-ink-muted">{{ implode(', ', $data['voterNames']) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <p class="font-serif text-lg italic text-ink-muted">Noch keine Stimmen abgegeben.</p>
                    <p class="text-sm text-ink-faint mt-2">Sei der Erste.</p>
                </div>
            @endif
        </section>

        {{-- Comments --}}
        @if($poll->allow_comments)
            <section class="pt-12">
                <div class="flex items-baseline justify-between pb-4 mb-10 border-b border-ink">
                    <h2 class="font-serif text-3xl font-bold tracking-tight">Kommentare</h2>
                    <span class="text-[10px] tracking-[0.2em] uppercase text-ink-faint tabular-nums">
                        {{ $poll->comments->count() }}
                    </span>
                </div>

                @if($poll->comments->count() > 0)
                    <div class="space-y-6 mb-10">
                        @foreach($poll->comments as $comment)
                            <div class="border-l-2 border-rule pl-4 py-1">
                                <div class="flex items-baseline gap-3">
                                    <span class="text-sm font-medium text-ink">{{ $comment->author_name }}</span>
                                    <span class="text-[10px] text-ink-faint">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-ink-muted mt-1">{{ $comment->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-ink-faint mb-10">Noch keine Kommentare.</p>
                @endif

                @if(!$poll->is_closed)
                    <form wire:submit="submitComment" class="space-y-4">
                        <div>
                            <label for="commentAuthor" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Name</label>
                            <input type="text" id="commentAuthor" wire:model="commentAuthor"
                                class="w-full max-w-xs px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors"
                                placeholder="Dein Name">
                            @error('commentAuthor') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="commentBody" class="block text-[10px] font-medium uppercase tracking-[0.2em] text-ink-muted mb-2">Kommentar</label>
                            <textarea id="commentBody" wire:model="commentBody" rows="3"
                                class="w-full px-4 py-3 text-sm border border-rule bg-cream text-ink placeholder-ink-faint focus:outline-none focus:border-ink transition-colors resize-none"
                                placeholder="Dein Kommentar..."></textarea>
                            @error('commentBody') <p class="mt-2 text-sm text-accent">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium bg-ink text-cream hover:bg-ink-light transition-colors cursor-pointer tracking-wide uppercase">
                            Kommentar senden
                        </button>
                    </form>
                @endif
            </section>
        @endif
    @endif
</div>
