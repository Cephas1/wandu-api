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
        $container = $container->groupBy('article_id');

        $c = [];
        foreach($container as $key => $values){

            $qte = 0;
            foreach($values as $value){
                $qte = $qte + $value->quantity;
            }

            if ($qte > 0){
                $c[] = [
                    'article' => $values[0]->article->name,
                    'article_id' => $values[0]->article->id,
                    'price_1'      => $values[0]->article->price_1,
                    'price_2'      => $values[0]->article->price_2,
                    'category'      => $values[0]->article->category->name,
                    'category_id'      => $values[0]->article->category->id,
                    'rubrique_id'      => $values[0]->article->rubrique->id,
                    'rubrique'      => $values[0]->article->rubrique->name,
                    'quantity' => $qte
                ];
            }
        }

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of container'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $c
        ]);
    }
}
