<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage_supplier extends Model
{
    protected $guarded = [];

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }
    public function color(){
        return $this->belongsTo('App\Models\Color');
    }
}
