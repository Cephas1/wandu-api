<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['client_id', 'article_id', 'user_id', 'quantity', 'price'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
