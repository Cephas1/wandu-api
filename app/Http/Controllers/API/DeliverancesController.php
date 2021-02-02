<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Liaison;
use App\Models\Shop_storage;
use App\Models\Notification;
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
        $deliverance_liaisons = Liaison::where([["deliverances","=", 1],["storage_id", "=", 1]])->orderBy('created_at', 'desc')->get()->load("shop");

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopDeliverance($id)
    {
        $deliverance_liaisons = Liaison::where([["deliverances", 1],["shop_id", $id]])->orderBy('created_at', 'desc')->get()->load("storage","shop_storage.article","shop_storage.color");

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supply liaisons and details'
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

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supply liaisons'
        ];

        $products = Container::where("storage_id", 1)->get();
        $products = $products->groupby("article_id");
        $products = $products->toArray();

        return response()->json([
            'meta' => $meta,
            'data' => $products
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
            'message'   => 'failure request'
        ];
        
        $deliverances = $request["deliverances"];
        $meta_data = $request["meta_data"];

        // Create the reference of deliverance (Storage to Shop)
        $liaison = array(
            "name"          => "DE" . rand(1, 99) . now()->dayOfYear,
            "number"        => rand(1, 99999999999),
            "deliverances"  => 1,
            "storage_id"    => $meta_data["storage_id"],
            "shop_id"       => $meta_data["shop_id"]
        );
        $liaison = Liaison::create($liaison);

        for($i = 0; $i < count($deliverances); $i++)
        {
            $deliverance = array(
                'article_id'        => $deliverances[$i]["article_id"],
                'color_id'          => $deliverances[$i]["color_id"],
                'quantity'          => $deliverances[$i]["quantity"],
                'liaison_id'        => $liaison->id,
                'storage_id'        => $meta_data["storage_id"],
                'shop_id'           => $meta_data["shop_id"],
                'user_id'           => $meta_data["user_id"],
                'date'              => date('Y-m-d')
            );

            $deliverance = Shop_storage::create($deliverance);

            // decrement of a specific container of storage
            $storage_container = Container::where([
                ["storage_id", $deliverance->storage_id],
                ["article_id", $deliverance->article_id],
                ["color_id", $deliverance->color_id]
            ])->first();

            $storage_container->quantity = $storage_container->quantity - $deliverance->quantity;
            $storage_container->save();
        }

        $notification_data = array(
            'type_notification_id'      => 1,
            'liaison_id'                => $liaison->id,
        );
        $notification = Notification::create($notification_data);

        if($notification){

            $meta['message'] = "Deliverance saved successful";

            return response()->json([
                'meta' => $meta,
                'data' => $liaison
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
        $deliverances = Shop_storage::where("liaison_id", $id)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of deliverances'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $deliverances
        ]);
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
