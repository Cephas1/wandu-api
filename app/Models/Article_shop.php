<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article_shop extends Model
{
    protected $fillable = ["article_id","date","quantity","price_got","shop_id","user_id","liaison_id", "color_id", "dtn", "time"];
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

    public function user(){
        return $this->belongsTo('App\User');
    }
}
