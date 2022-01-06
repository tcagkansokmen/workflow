<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefComment extends Model
{
    protected $table = 'brief_comments';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\BriefCommentFile', 'brief_comment_id', 'id');
    }
}
