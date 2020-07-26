<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['furnisher_id', 'article_id', 'user_id', 'quantity', 'price'];

    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }

    public function furnisher()
    {
        return $this->belongsTo('App\Models\Furnisher');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
