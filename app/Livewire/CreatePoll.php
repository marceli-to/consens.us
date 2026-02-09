<?php

namespace App\Livewire;

use App\Models\Poll;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatePoll extends Component
{
    public string $title = '';
    public string $description = '';
    public string $type = 'freetext';
    public string $votingMode = 'checkbox';
    public array $options = ['', ''];
    public string $password = '';

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption(int $index)
    {
        if (count($this->options) > 2) {
            array_splice($this->options, $index, 1);
            $this->options = array_values($this->options);
        }
    }

    public function submit()
    {
        $this->validate([
            'title' => 'required|min:2|max:255',
            'type' => 'required|in:freetext,date',
            'votingMode' => 'required|in:checkbox,radio,yesnomaybe',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|min:1',
        ], [
            'title.required' => 'Bitte gib einen Titel ein.',
            'options.*.required' => 'Bitte fülle alle Optionen aus.',
        ]);

        $poll = Poll::create([
            'title' => $this->title,
            'description' => $this->description ?: null,
            'type' => $this->type,
            'voting_mode' => $this->votingMode,
            'password' => $this->password ? Hash::make($this->password) : null,
        ]);

        foreach ($this->options as $i => $label) {
            $poll->options()->create([
                'label' => $this->type === 'date' ? \Carbon\Carbon::parse($label)->translatedFormat('l, j. F Y') : $label,
                'sort_order' => $i,
            ]);
        }

        return redirect("/p/{$poll->slug}/edit/{$poll->edit_token}");
    }

    public function render()
    {
        return view('livewire.create-poll');
    }
}
