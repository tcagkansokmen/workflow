<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferMessageFile extends Model
{
    protected $table = 'offer_message_files';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
