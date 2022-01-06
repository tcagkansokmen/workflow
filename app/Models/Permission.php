<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $guarded = ['id'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\PermissionType', 'type', 'id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function total()
    {
        $aa = Permission::where('user_id', $this->user_id)
        ->where('type', $this->type)
        ->where('start_at', '<', $this->start_at)
        ->where('status', 'Onaylandı')->sum('days');
        return $aa;
    }
}
