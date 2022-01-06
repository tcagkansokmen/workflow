<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferComment extends Model
{
    protected $table = 'offer_comments';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

}
