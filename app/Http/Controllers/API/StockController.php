<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Container;

class StockController extends Controller
{
    public function index() {

        $container = Container::where('storage_id', $id)->orderBy('updated_at', 'desc')->get()->load('article.category', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Stock of articles'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $stock
        ]);
    }
}
