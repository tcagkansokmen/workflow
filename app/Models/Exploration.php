<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exploration extends Model
{
    use HasFactory;
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User')->withDefault(array('name' => '-', 'surname' => '-'));
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withDefault(array('title' => '-'));
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project')->withDefault(array('title' => '-'));
    }
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ExplorationMessage');
    }
    public function extras(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ExplorationExtra');
    }
    public function county()
    {
        return $this->belongsTo('App\Models\County');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function designs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\ExplorationDesign');
    }
}
