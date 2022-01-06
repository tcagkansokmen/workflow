<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    use HasFactory;
    public function buttons()
    {
        return $this->hasMany('App\Models\Workflow', 'parent_sef', 'sef')->groupBy('sef');
    }
    public function groups()
    {
        return $this->hasMany('App\Models\Workflow', 'sef', 'sef')->whereNotNull('user_group_id')->select('user_group_id');
    }
}
