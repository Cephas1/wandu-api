<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ["name", "description", "price_1", "price_2", "price_3", "price_4", "imageuri", "category_id", "rubrique_id"];
    protected $hidden = ["deleted_at"];

    public function rubrique(){
        return $this->belongsTo('App\Models\Rubrique');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }
}
