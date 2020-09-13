<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = ["name", "location", "email", "phone"];
    protected $hidden = ["deleted_at"];
}
