<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function liaison(){
        return $this->belongsTo('App\Models\Liaison');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
