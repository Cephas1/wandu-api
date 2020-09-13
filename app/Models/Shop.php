<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $hidden = ["deleted_at"];
    protected $fillable = ["name", "location", "email", "phone"];
}
