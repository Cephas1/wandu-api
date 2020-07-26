<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $timestamp = false;

    protected $fillable = ['name','code', 'unity'];

    protected $hidden = ['deleted_at'];
}
