<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function getPicture($path) {

        $ab_path = public_path(str_replace('$','\\',$path));

        return response()->file($ab_path);
        //return response()->json($ab_path);
    }
}
