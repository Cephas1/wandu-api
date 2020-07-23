<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['furnisher', 'product', 'quantity', 'date'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
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
