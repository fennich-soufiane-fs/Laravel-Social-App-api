<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'text',
        'image',
        'visibility',
    ];

    public function comments()
    {
    return $this->hasMany(Comment::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(like::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
