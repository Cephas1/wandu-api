<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fond extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function shop(){
        return $this->belongsTo('App\Models\Shop');
    }
}
