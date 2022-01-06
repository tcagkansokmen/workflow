<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferMessage extends Model
{
    protected $table = 'offer_messages';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\OfferMessageFile', 'offer_message_id', 'id');
    }
    public function offer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Offer', 'offer_id', 'id');
    }


}
