<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'prix', 'category_id', 'imageuri'];

    public function categories()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
