<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandType extends Model
{
    protected $table = 'stand_types';
    protected $guarded = ['id'];

    public function parents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\StandType', 'title', 'title')->groupBy('parent');
    }
    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\StandType', 'parent', 'parent');
    }
    public function brieftype(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\BriefType', 'key', 'name');
    }
    
}
