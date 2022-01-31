<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Comment extends Base
{
    protected $fillable = [
        'user_id',
        'post_id',
        'description',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that owns the post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
