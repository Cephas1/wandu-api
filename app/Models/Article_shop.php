<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article_shop extends Model
{
    protected $guarded = [];
    protected $hidden = ["deleted_at"];

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }

    public function liaison(){
        return $this->belongsTo('App\Models\Liaison');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
