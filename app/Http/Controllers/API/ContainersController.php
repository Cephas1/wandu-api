<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class ContainersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStorageContainer($id)
    {
        $container = Container::where('storage_id', $id)->orderBy('updated_at', 'desc')->get()->load('article.category', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of container'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $container
        ]);
    }

    public function showShopContainer($id)
    {
        $container = Container::where('shop_id', $id)->orderBy('updated_at', 'desc')->get()->load('article.category', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of container'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $container
        ]);
    }
}
