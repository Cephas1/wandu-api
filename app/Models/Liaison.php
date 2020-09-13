<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liaison extends Model
{
    protected $fillable = ["name", "number", "storage_id", "shop_id"];
    protected $hidden = ["deleted_at"];
}
