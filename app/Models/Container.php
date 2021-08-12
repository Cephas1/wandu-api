<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $guarded = [];

    public function lot(){
        return $this->belongsTo('App\Models\Lot');
    }

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }

    public function shop(){
        return $this->belongsTo('App\Models\Shop');
    }

    public function storage(){
        return $this->belongsTo('App\Models\Storage');
    }
}
