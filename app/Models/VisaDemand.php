<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaDemand extends Model
{
    protected $table = 'visa_demands';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
