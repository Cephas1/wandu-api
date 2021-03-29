<?php

namespace App\Http\Controllers\API;

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStorageContainer($id)
    {
        $container = Container::where('storage_id', $id)->orderBy('updated_at', 'desc')->get()->load('article.category', 'color');
        $storage_suppliers = Storage_supplier::select('article_id','color_id','storage_id','date', 'time', 'quantity', 'price_gave')->where(['storage_id' => $id, 'deleted_at' => null])->get()->load('article', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of container'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => ['container' => $container, 'storageEntries' => $storage_suppliers]
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
