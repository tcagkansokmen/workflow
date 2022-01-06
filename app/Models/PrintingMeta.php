<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingMeta extends Model
{
    use HasFactory;
    public function options()
    {
        return $this->hasMany('App\Models\PrintingMeta', 'key', 'key')->where('type', 'options');
    }
    public function products()
    {
        return $this->hasMany('App\Models\PrintingMeta', 'key', 'key')->where('type', 'content');
    }
}
