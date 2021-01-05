<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function liaison(){
        return $this->belongsTo('App\Models\Liaison');
    }

    public function type(){
        return $this->belongsTo('App\Models\Type_notification');
    }
}
