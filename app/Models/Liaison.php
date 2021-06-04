<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liaison extends Model
{
    protected $fillable = ["name", "number", "storage_id", "user_id", "shop_id", "supplier_id", "deliverances", "provides", "purchases", "date", "time"];
    protected $hidden = ["deleted_at"];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }
    ////////////////////////////
    public function article(){
        return $this->belongsTo('App\Models\Article');
    }

    public function color(){
        return $this->belongsTo('App\Models\Color');
    }
    ////////////////////////////
    public function shop(){
        return $this->belongsTo('App\Models\Shop');
    }

    public function storage(){
        return $this->belongsTo('App\Models\Storage');
    }

    public function shop_storage(){
        return $this->hasMany('App\Models\Shop_storage');
    }

    public function storage_suppliers(){
        return $this->hasMany('App\Models\Storage_supplier');
    }

    public function article_shop(){
        return $this->belongsTo('App\Models\Article_shop');
    }

    public function container(){
        return $this->belongsTo('App\Models\Container');
    }
}
