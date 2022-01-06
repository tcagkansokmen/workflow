<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingMessage extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(array('name' => '-', 'surname' => '-'));
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\PrintingMessageFile');
    }
}
