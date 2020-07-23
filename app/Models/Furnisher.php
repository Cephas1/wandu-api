<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Furnisher extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email'];
}
