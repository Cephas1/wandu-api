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
    
    public function liaison(){
        return $this->belongsTo('App\Models\Liaison');
    }
    
    public function storage(){
        return $this->belongsTo('App\Models\Storage');
    }
    
    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
