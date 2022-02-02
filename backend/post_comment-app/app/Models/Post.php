<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Post extends Base
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'views',
        'like',
        'dislike',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
