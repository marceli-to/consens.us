<?php

namespace App\Livewire;

use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PollView extends Component
{
    public Poll $poll;
    public string $voterName = '';
    public array $selectedOptions = [];
    public bool $hasVoted = false;
    public bool $needsPassword = false;
    public string $password = '';
    public string $passwordError = '';

    public function mount(string $slug)
    {
        $this->poll = Poll::where('slug', $slug)->with('options', 'votes')->firstOrFail();

        if ($this->poll->isPasswordProtected() && !session("poll_{$slug}_auth")) {
            $this->needsPassword = true;
        }

        $this->checkExistingVote();
    }

    public function authenticate()
    {
        if (Hash::check($this->password, $this->poll->password)) {
            session(["poll_{$this->poll->slug}_auth" => true]);
            $this->needsPassword = false;
            $this->password = '';
            $this->passwordError = '';
        } else {
            $this->passwordError = 'Falsches Passwort.';
            $this->password = '';
        }
    }

    protected function checkExistingVote()
    {
        $voteId = session("poll_{$this->poll->slug}_vote_id");
        if ($voteId) {
            $exists = Vote::where('poll_id', $this->poll->id)->where('voter_name', $voteId)->exists();
            if ($exists) {
                $this->hasVoted = true;
                $this->voterName = $voteId;
                $this->selectedOptions = Vote::where('poll_id', $this->poll->id)
                    ->where('voter_name', $voteId)
                    ->pluck('poll_option_id')
                    ->map(fn($id) => (string) $id)
                    ->toArray();
            }
        }
    }

    public function submitVote()
    {
        $this->validate([
            'voterName' => 'required|min:2|max:100',
            'selectedOptions' => 'required|array|min:1',
        ], [
            'voterName.required' => 'Bitte gib deinen Namen ein.',
            'selectedOptions.required' => 'Bitte wähle mindestens eine Option.',
            'selectedOptions.min' => 'Bitte wähle mindestens eine Option.',
        ]);

        // Delete existing votes for this voter
        Vote::where('poll_id', $this->poll->id)
            ->where('voter_name', $this->voterName)
            ->delete();

        // Create new votes
        foreach ($this->selectedOptions as $optionId) {
            Vote::create([
                'poll_id' => $this->poll->id,
                'poll_option_id' => $optionId,
                'voter_name' => $this->voterName,
            ]);
        }

        session(["poll_{$this->poll->slug}_vote_id" => $this->voterName]);
        $this->hasVoted = true;
    }

    public function editVote()
    {
        $this->hasVoted = false;
    }

    public function getResultsProperty()
    {
        $this->poll->load('options', 'votes');
        $votes = $this->poll->votes;
        $voterNames = $votes->pluck('voter_name')->unique();
        $totalVoters = $voterNames->count();

        $optionResults = [];
        foreach ($this->poll->options as $option) {
            $count = $votes->where('poll_option_id', $option->id)->count();
            $optionResults[] = [
                'id' => $option->id,
                'label' => $option->label,
                'count' => $count,
                'percentage' => $totalVoters > 0 ? round(($count / $totalVoters) * 100) : 0,
            ];
        }

        usort($optionResults, fn($a, $b) => $b['count'] <=> $a['count']);

        // Build voter table
        $voters = [];
        foreach ($voterNames as $name) {
            $voterVotes = $votes->where('voter_name', $name)->pluck('poll_option_id')->toArray();
            $voters[] = [
                'name' => $name,
                'options' => $voterVotes,
            ];
        }

        return [
            'totalVoters' => $totalVoters,
            'options' => $optionResults,
            'voters' => $voters,
        ];
    }

    public function render()
    {
        return view('livewire.poll-view', [
            'results' => $this->results,
        ]);
    }
}
