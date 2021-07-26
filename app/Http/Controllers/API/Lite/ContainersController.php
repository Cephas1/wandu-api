<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Storage_supplier;
use Illuminate\Http\Request;

class ContainersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContainer(Request $request)
    {
        $shop_id = $request['shop_id']??null;
        $storage_id = $request['storage_id']??null;

        $container = Container::where([['shop_id', $shop_id], ['storage_id', $storage_id]])->orderBy('updated_at', 'desc')->get()->load('article.category', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Stock of articles'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $container
        ]);
    }

    public function showShopContainer($id)
    {
        $container = Container::where('shop_id', $id)->orderBy('updated_at', 'desc')->get()->load('article.category', 'article.rubrique');

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
