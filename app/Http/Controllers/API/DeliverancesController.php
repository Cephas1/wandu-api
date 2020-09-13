<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Liaison;
use App\Models\Shop_storage;
use Illuminate\Http\Request;

class DeliverancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliverance_liaisons = Liaison::where([["storage_id", "=", null], ["shop_id", "=", null]])->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supply liaisons'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $deliverance_liaisons
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'message'   => 'failure request'
        ];
        $deliverances = $request["deliverances"];

        // Create the reference of deliverance (Storage to Shop)
        $liaison = array(
            "name"          => "DE" . rand(1, 99) . now()->dayOfYear,
            "number"        => rand(1, 99999999999)
        );
        $liaison = Liaison::create($liaison);

        for($i = 0; $i < count($deliverances); $i++)
        {
            $deliverance = array(
                'article_id'        => $deliverances[$i]["article_id"],
                'color_id'          => $deliverances[$i]["color_id"],
                'quantity'          => $deliverances[$i]["quantity"],
                'liaison_id'        => $liaison->id,
                'storage_id'        => 1,
                'shop_id'        => 1,
                'user_id'           => 1,
                'date'              => now()
            );

            $deliverance = Shop_storage::create($deliverance);

            if($deliverance)
            {
                // decrement of a specific container of storage
                $storage_container = Container::where([
                    ["storage_id", $deliverance->storage_id],
                    ["article_id", $deliverance->article_id],
                    ["color_id", $deliverance->color_id]
                ])->first();
                $storage_container->quantity = $storage_container->quantity - $deliverance->quantity;
                $storage_container->save();

                // increment of a specific container of shop
                $shop_container = Container::where([
                    ["shop_id", $deliverance->shop_id],
                    ["article_id", $deliverance->article_id],
                    ["color_id", $deliverance->color_id]
                ])->first();
                if($shop_container){
                    $shop_container->quantity = $shop_container->quantity + $deliverance->quantity;
                    $shop_container->save();
                }else{
                    $container = array(
                        "article_id"        => $deliverance->article_id,
                        "shop_id"        => $deliverance->shop_id,
                        "color_id"          => $deliverance->color_id,
                        "quantity"          => $deliverance->quantity
                    );
                    Container::create($container);
                }

                $meta['message'] = "Deliverance saved successful";
            }

            return response()->json([
                'meta' => $meta,
                'data' => $liaison->name
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
