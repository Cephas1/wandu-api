<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function getPicture($url) {

        $ab_path = public_path($url);
        
        return response()->download($ab_path);
    }
}
