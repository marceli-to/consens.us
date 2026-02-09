<?php

namespace App\Livewire;

use App\Models\Poll;
use Livewire\Component;

class PollEdit extends Component
{
    public Poll $poll;
    public string $title = '';
    public string $description = '';
    public array $options = [];
    public string $newOption = '';
    public bool $allowComments = true;
    public bool $showDeleteConfirm = false;
    public string $publicUrl = '';
    public string $editUrl = '';

    public function mount(string $slug, string $token)
    {
        $this->poll = Poll::where('slug', $slug)->where('edit_token', $token)->with('options')->firstOrFail();
        $this->title = $this->poll->title;
        $this->description = $this->poll->description ?? '';
        $this->options = $this->poll->options->map(fn($o) => ['id' => $o->id, 'label' => $o->label])->toArray();
        $this->allowComments = $this->poll->allow_comments;
        $this->publicUrl = url("/p/{$this->poll->slug}");
        $this->editUrl = url("/p/{$this->poll->slug}/edit/{$this->poll->edit_token}");
    }

    public function saveDetails()
    {
        $this->validate([
            'title' => 'required|min:2|max:255',
        ]);

        $this->poll->update([
            'title' => $this->title,
            'description' => $this->description ?: null,
        ]);
    }

    public function addOption()
    {
        if (empty(trim($this->newOption))) return;

        $maxSort = $this->poll->options()->max('sort_order') ?? 0;
        $option = $this->poll->options()->create([
            'label' => $this->newOption,
            'sort_order' => $maxSort + 1,
        ]);

        $this->options[] = ['id' => $option->id, 'label' => $option->label];
        $this->newOption = '';
    }

    public function removeOption(int $optionId)
    {
        $this->poll->options()->where('id', $optionId)->delete();
        $this->poll->votes()->where('poll_option_id', $optionId)->delete();
        $this->options = array_values(array_filter($this->options, fn($o) => $o['id'] !== $optionId));
    }

    public function toggleComments()
    {
        $this->allowComments = !$this->allowComments;
        $this->poll->update(['allow_comments' => $this->allowComments]);
        $this->poll->refresh();
    }

    public function toggleClose()
    {
        $this->poll->update(['is_closed' => !$this->poll->is_closed]);
        $this->poll->refresh();
    }

    public function deletePoll()
    {
        $this->poll->delete();
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.poll-edit');
    }
}
