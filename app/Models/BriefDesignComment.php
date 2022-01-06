<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefDesignComment extends Model
{
    protected $table = 'brief_design_comments';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\BriefDesignCommentFile', 'brief_design_comment_id', 'id');
    }

}
