<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article_shop;
use App\Models\Container;
use App\Models\Liaison;
use Carbon\Carbon;
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
        $currentDate = date('y-m-d');

        $purchases = Article_shop::where('dtn', $currentDate)->get();

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
    public function getcontainer($id)
    {
        $containers = Container::where([['shop_id', $id],['quantity', '>', 0]])->get()->load('article', 'color');

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

        $meta_data = $request["meta_data"];
        $purchases = $request["purchases"];

        $liaison = array(
            "name"      => "PU" . rand(1, 99) . now()->dayOfYear,
            "number"    => rand(1, 99999999999),
            "purchases" => 1,
            "shop_id"   => $meta_data["shop_id"],
            "user_id"   => $meta_data["user_id"],
            "date"      => date('Y-m-d'),
            "time"      => date('H:i:s')
        );
        $liaison = Liaison::create($liaison);

        for($i = 0; $i < count($purchases); $i++)
        {
            // Verify if this article is in the shop container
            $container = Container::where([
                ["shop_id", $meta_data["shop_id"]],
                ["article_id", $purchases[$i]["article_id"]],
                ["color_id", $purchases[$i]["color_id"]]
            ])->first();

            if($container) {

                $purchase = array(
                    "article_id"        => $purchases[$i]["article_id"],
                    "color_id"        => $purchases[$i]["color_id"],
                    "quantity"        => $purchases[$i]["quantity"],
                    "price_got"        => $purchases[$i]["price_got"],
                    "shop_id"        => $meta_data["shop_id"],
                    "user_id"        => $meta_data["user_id"],
                    "liaison_id"        => $liaison->id,
                    "date"        => date('Y-m-d'),
                    "time"        => date('H:i:s')
                );
                $purchase = Article_shop::create($purchase);

                // Decrement the quantity of article bought
                $container->quantity = $container->quantity - $purchase->quantity;
                $container->save();
            }else $meta['message'] = "L'article n'est pas dans notre stock";
        }

        return response()->json([
            'meta' => $meta,
            'data' => $liaison
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

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Failure request'
        ];

        $liaison = Liaison::find($id);

        $meta_data = $request["meta_data"];
        $new_purchases = $request["purchases"];

        $old_purchases = Article_shop::where('liaison_id', $liaison->id)->get();

        for($i = 0; $i < count($new_purchases); $i++)
        {
            $purchase_id = $new_purchases[$i]["purchase_id"];
            $purchase_article_id = $new_purchases[$i]["article_id"];
            $purchase_color_id = $new_purchases[$i]["color_id"];
            $purchase_quantity = $new_purchases[$i]["quantity"];
            $purchase_price_got = $new_purchases[$i]["price_got"];

            foreach($old_purchases as $purchase){
                if($purchase->id == $purchase_id){

                    if($purchase->color_id == $purchase_color_id){

                        $container = Container::where([
                            ["shop_id", $purchase->shop_id],
                            ["article_id", $purchases[$i]["article_id"]],
                            ["color_id", $new_purchases[$i]["color_id"]]
                        ])->first();

                        $container->quantity = $container->quantity + ($purchase_quantity - $purchase->quantity);
                        $container->save();
                    }else{

                        $container = Container::where([
                            ["shop_id", $purchase->shop_id],
                            ["article_id", $purchase->article_id],
                            ["color_id", $purchase->color_id]
                        ])->first();

                        $container->quantity = $container->quantity + $purchase->quantity;
                        $container->save();

                        $container = Container::where([
                            ["shop_id", $purchase->shop_id],
                            ["article_id", $purchase->article_id],
                            ["color_id", $new_purchases[$i]["color_id"]]
                        ])->first();

                        $container->quantity = $container->quantity - $purchase_quantity;
                        $container->save();

                    }

                    $purchase->article_id = $new_purchases[$i]["article_id"];
                    $purchase->color_id = $new_purchases[$i]["color_id"];
                    $purchase->quantity = $new_purchases[$i]["quantity"];
                    $purchase->price_got = $new_purchases[$i]["price_got"];

                    $purchase->save();
                }
            }
        }


        return response()->json([
            'meta' => $meta
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
