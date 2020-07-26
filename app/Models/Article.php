<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['name','description', 'price', 'quantity', 'category_id'];

    public function categories()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
