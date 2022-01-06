<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Earnest extends Model
{
    protected $table = 'earnests';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
