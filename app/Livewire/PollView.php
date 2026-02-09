<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PollView extends Component
{
    public Poll $poll;
    public string $voterName = '';
    public array $selectedOptions = [];
    public string $selectedOption = '';
    public array $optionValues = [];
    public bool $hasVoted = false;
    public bool $needsPassword = false;
    public string $password = '';
    public string $passwordError = '';

    // Comments
    public string $commentAuthor = '';
    public string $commentBody = '';

    public function mount(string $slug)
    {
        $this->poll = Poll::where('slug', $slug)->with('options', 'votes', 'comments')->firstOrFail();

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
        if (!$voteId) return;

        $exists = Vote::where('poll_id', $this->poll->id)->where('voter_name', $voteId)->exists();
        if (!$exists) return;

        $this->hasVoted = true;
        $this->voterName = $voteId;

        if ($this->poll->isYesNoMaybe()) {
            $votes = Vote::where('poll_id', $this->poll->id)
                ->where('voter_name', $voteId)
                ->get();
            $this->optionValues = [];
            foreach ($votes as $vote) {
                $this->optionValues[(string) $vote->poll_option_id] = $vote->value;
            }
        } elseif ($this->poll->isSingleChoice()) {
            $vote = Vote::where('poll_id', $this->poll->id)
                ->where('voter_name', $voteId)
                ->first();
            $this->selectedOption = $vote ? (string) $vote->poll_option_id : '';
        } else {
            $this->selectedOptions = Vote::where('poll_id', $this->poll->id)
                ->where('voter_name', $voteId)
                ->pluck('poll_option_id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        }
    }

    public function submitVote()
    {
        if ($this->poll->isYesNoMaybe()) {
            $this->submitYesNoMaybe();
        } elseif ($this->poll->isSingleChoice()) {
            $this->submitSingleChoice();
        } else {
            $this->submitMultiChoice();
        }

        session(["poll_{$this->poll->slug}_vote_id" => $this->voterName]);
        $this->hasVoted = true;
        $this->commentAuthor = $this->voterName;
    }

    protected function submitMultiChoice()
    {
        $this->validate([
            'voterName' => 'required|min:2|max:100',
            'selectedOptions' => 'required|array|min:1',
        ], [
            'voterName.required' => 'Bitte gib deinen Namen ein.',
            'selectedOptions.required' => 'Bitte wähle mindestens eine Option.',
            'selectedOptions.min' => 'Bitte wähle mindestens eine Option.',
        ]);

        Vote::where('poll_id', $this->poll->id)
            ->where('voter_name', $this->voterName)
            ->delete();

        foreach ($this->selectedOptions as $optionId) {
            Vote::create([
                'poll_id' => $this->poll->id,
                'poll_option_id' => $optionId,
                'voter_name' => $this->voterName,
            ]);
        }
    }

    protected function submitSingleChoice()
    {
        $this->validate([
            'voterName' => 'required|min:2|max:100',
            'selectedOption' => 'required',
        ], [
            'voterName.required' => 'Bitte gib deinen Namen ein.',
            'selectedOption.required' => 'Bitte wähle eine Option.',
        ]);

        Vote::where('poll_id', $this->poll->id)
            ->where('voter_name', $this->voterName)
            ->delete();

        Vote::create([
            'poll_id' => $this->poll->id,
            'poll_option_id' => $this->selectedOption,
            'voter_name' => $this->voterName,
        ]);
    }

    protected function submitYesNoMaybe()
    {
        $this->validate([
            'voterName' => 'required|min:2|max:100',
            'optionValues' => 'required|array|min:1',
            'optionValues.*' => 'required|in:yes,no,maybe',
        ], [
            'voterName.required' => 'Bitte gib deinen Namen ein.',
            'optionValues.required' => 'Bitte bewerte alle Optionen.',
            'optionValues.min' => 'Bitte bewerte mindestens eine Option.',
            'optionValues.*.required' => 'Bitte bewerte alle Optionen.',
        ]);

        Vote::where('poll_id', $this->poll->id)
            ->where('voter_name', $this->voterName)
            ->delete();

        foreach ($this->optionValues as $optionId => $value) {
            Vote::create([
                'poll_id' => $this->poll->id,
                'poll_option_id' => $optionId,
                'voter_name' => $this->voterName,
                'value' => $value,
            ]);
        }
    }

    public function editVote()
    {
        $this->hasVoted = false;
    }

    public function submitComment()
    {
        $this->validate([
            'commentAuthor' => 'required|min:2|max:100',
            'commentBody' => 'required|min:1|max:2000',
        ], [
            'commentAuthor.required' => 'Bitte gib deinen Namen ein.',
            'commentBody.required' => 'Bitte schreibe einen Kommentar.',
        ]);

        Comment::create([
            'poll_id' => $this->poll->id,
            'author_name' => $this->commentAuthor,
            'body' => $this->commentBody,
        ]);

        $this->commentBody = '';
        $this->poll->load('comments');
    }

    public function getResultsProperty()
    {
        $this->poll->load('options', 'votes');
        $votes = $this->poll->votes;
        $voterNames = $votes->pluck('voter_name')->unique();
        $totalVoters = $voterNames->count();

        if ($this->poll->isYesNoMaybe()) {
            return $this->getYesNoMaybeResults($votes, $voterNames, $totalVoters);
        }

        $optionResults = [];
        foreach ($this->poll->options as $option) {
            $optionVotes = $votes->where('poll_option_id', $option->id);
            $count = $optionVotes->count();
            $optionResults[] = [
                'id' => $option->id,
                'label' => $option->label,
                'count' => $count,
                'percentage' => $totalVoters > 0 ? round(($count / $totalVoters) * 100) : 0,
                'voterNames' => $optionVotes->pluck('voter_name')->toArray(),
            ];
        }

        if ($this->poll->type !== 'date') {
            usort($optionResults, fn($a, $b) => $b['count'] <=> $a['count']);
        }

        return [
            'totalVoters' => $totalVoters,
            'options' => $optionResults,
        ];
    }

    protected function getYesNoMaybeResults($votes, $voterNames, $totalVoters)
    {
        $optionResults = [];
        foreach ($this->poll->options as $option) {
            $optionVotes = $votes->where('poll_option_id', $option->id);
            $yesCount = $optionVotes->where('value', 'yes')->count();
            $noCount = $optionVotes->where('value', 'no')->count();
            $maybeCount = $optionVotes->where('value', 'maybe')->count();
            $score = ($yesCount * 2) + ($maybeCount * 1) + ($noCount * 0);

            $optionResults[] = [
                'id' => $option->id,
                'label' => $option->label,
                'yes' => $yesCount,
                'no' => $noCount,
                'maybe' => $maybeCount,
                'score' => $score,
                'yesPercent' => $totalVoters > 0 ? round(($yesCount / $totalVoters) * 100) : 0,
                'noPercent' => $totalVoters > 0 ? round(($noCount / $totalVoters) * 100) : 0,
                'maybePercent' => $totalVoters > 0 ? round(($maybeCount / $totalVoters) * 100) : 0,
                'yesVoters' => $optionVotes->where('value', 'yes')->pluck('voter_name')->toArray(),
                'maybeVoters' => $optionVotes->where('value', 'maybe')->pluck('voter_name')->toArray(),
                'noVoters' => $optionVotes->where('value', 'no')->pluck('voter_name')->toArray(),
            ];
        }

        if ($this->poll->type !== 'date') {
            usort($optionResults, fn($a, $b) => $b['score'] <=> $a['score']);
        }

        return [
            'totalVoters' => $totalVoters,
            'options' => $optionResults,
        ];
    }

    public function render()
    {
        return view('livewire.poll-view', [
            'results' => $this->results,
        ]);
    }
}
