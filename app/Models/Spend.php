<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spend extends Model
{
    protected  $guarded = [];

    public function spendtype(){
        return $this->belongsTo('App\Models\SpendType');
    }

    public function shop(){
        return $this->belongsTo('App\Models\Shop');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
