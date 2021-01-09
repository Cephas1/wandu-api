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

        $liaison = array(
            "name"      => "PU" . rand(1, 99) . now()->dayOfYear,
            "number"    => rand(1, 99999999999),
            "purchases" => 1,
            "shop_id"   => 1
        );
        $liaison = Liaison::create($liaison);

        $purchases = $request["purchases"];
        for($i = 0; $i < count($purchases); $i++)
        {
            // Verify if this article is in the shop container
            $container = Container::where([
                ["shop_id", 3],
                ["article_id", $purchases[$i]["article_id"]],
                ["color_id", $purchases[$i]["color_id"]]
            ])->first();

            if($container) {

                $purchase = array(
                    "article_id"        => $purchases[$i]["article_id"],
                    "color_id"        => $purchases[$i]["color_id"],
                    "quantity"        => $purchases[$i]["quantity"],
                    "price_got"        => $purchases[$i]["price_got"],
                    "date"        => now(),
                    "dtn"         => date('y-m-d'),
                    "time"        => date('H:i:s'),
                    "shop_id"        => 1,
                    "user_id"        => 1,
                    "liaison_id"        => $liaison->id
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
