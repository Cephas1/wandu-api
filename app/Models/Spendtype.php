<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spendtype extends Model
{
    protected $fillable = ["name"];
    protected $hidden = ["deleted_at"];
}
