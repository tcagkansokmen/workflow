<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicHoliday extends Model
{
    protected $table = 'public_holidays';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
}
