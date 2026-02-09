<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['poll_id', 'author_name', 'body'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}
