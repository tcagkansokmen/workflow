<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Belonging extends Model
{
    protected $table = 'belongings';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
