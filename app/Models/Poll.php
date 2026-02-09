<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poll extends Model
{
    protected $fillable = ['slug', 'edit_token', 'title', 'description', 'password', 'type', 'voting_mode', 'allow_comments', 'is_closed'];

    protected function casts(): array
    {
        return [
            'allow_comments' => 'boolean',
            'is_closed' => 'boolean',
        ];
    }

    public static function booted(): void
    {
        static::creating(function (Poll $poll) {
            if (!$poll->slug) {
                $poll->slug = Str::random(8);
            }
            if (!$poll->edit_token) {
                $poll->edit_token = Str::random(32);
            }
        });
    }

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('sort_order');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    public function isYesNoMaybe(): bool
    {
        return $this->voting_mode === 'yesnomaybe';
    }

    public function isSingleChoice(): bool
    {
        return $this->voting_mode === 'radio';
    }

    public function isMultiChoice(): bool
    {
        return $this->voting_mode === 'checkbox';
    }
}
