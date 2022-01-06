<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionType extends Model
{
    protected $table = 'permission_types';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function used(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Permission', 'type', 'id');
    }
}
