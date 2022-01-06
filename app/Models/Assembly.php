<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assembly extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer')->withDefault(array('title' => '-'));
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project')->withDefault(array('title' => '-'));
    }
    public function log(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\Log');
    }
    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\AssemblyMessage');
    }
    public function logs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\WofkflowLog');
    }
    public function extras(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\AssemblyExtra');
    }
    public function bills(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Models\Bill', 'bill_projects', 'assembly_id', 'bill_id')->withPivot('id');
    }
}
