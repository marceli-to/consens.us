<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poll extends Model
{
    protected $fillable = ['slug', 'edit_token', 'title', 'description', 'password', 'type', 'is_closed'];

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

    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }
}
