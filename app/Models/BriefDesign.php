<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefDesign extends Model
{
    protected $table = 'brief_designs';
    protected $guarded = ['id'];

    public function designs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\BriefDesignDetail', 'design_id', 'id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\BriefDesignComment', 'design_id', 'id');
    }

}
