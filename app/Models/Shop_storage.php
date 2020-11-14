<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop_storage extends Model
{
    protected $guarded = [];

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }
}
