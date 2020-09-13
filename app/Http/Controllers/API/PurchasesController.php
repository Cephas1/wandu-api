<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article_shop;
use App\Models\Container;
use App\Models\Liaison;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Validator;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Liaison::where("shop_id", 1)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of purchases'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $purchases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $containers = Container::where("shop_id", 1)->get()->load('article', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of shop containers'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $containers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Failure request'
        ];

        $liaison = array(
            "name"      => "PU" . rand(1, 99) . now()->dayOfYear,
            "number"    => rand(1, 99999999999),
            "shop_id"   => 1
        );
        $liaison = Liaison::create($liaison);

        $purchases = $request["purchases"];
        for($i = 0; $i < count($purchases); $i++)
        {
            $purchase = array(
                "article_id"        => $purchases[$i]["article_id"],
                "color_id"        => $purchases[$i]["color_id"],
                "quantity"        => $purchases[$i]["quantity"],
                "price_got"        => $purchases[$i]["price_got"],
                "date"        => now(),
                "shop_id"        => 1,
                "user_id"        => 1,
                "liaison_id"        => $liaison->id
            );

            $purchase = Article_shop::create($purchase);

            if($purchase)
            {
                // Add the provided article to the container(Stock) of a specific shop

                $container = Container::where([
                    ["shop_id", $purchase->shop_id],
                    ["article_id", $purchase->article_id],
                    ["color_id", $purchase->color_id]
                ])->first();

                if($container){
                    $container->quantity = $container->quantity - $purchase->quantity;
                    $container->save();
                }else{
                    $container = array(
                        "article_id"        => $purchase->article_id,
                        "shop_id"        => $purchase->storage_id,
                        "color_id"          => $purchase->color_id,
                        "quantity"          => $purchase->quantity
                    );
                    Container::create($container);
                }
                $meta['message'] = "Purchase saved successful";
            }
        }

        return response()->json([
            'meta' => $meta,
            'data' => $liaison->name
        ]);
    }

    /**
     * Display the list of purchase of specified liaison resource.
     * Need liaison int $id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchases = Article_shop::where("liaison_id", $id)->get()->load("article", "color");

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Purchase's details"
        ];
        if($purchases == null){
            $meta['message'] = "No data corresponded";
        }

        return response()->json([
            'meta' => $meta,
            'data' => $purchases
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
