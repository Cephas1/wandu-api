<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Liaison;
use App\Models\Storage_supplier;
use Illuminate\Http\Request;

class SuppliesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supply_liaisons = Liaison::where("storage_id", 1)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supply liaisons'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $supply_liaisons
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $containers = Container::where("storage_id", 1)->get()->load('article', 'color');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of storage containers'
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

        // Create the reference of supplies
        $liaison = array(
            "name"          => "SUP" . rand(1, 99) . now()->dayOfYear,
            "number"        => rand(1, 99999999999),
            "storage_id"    => 1
        );
        $liaison = Liaison::create($liaison);

        $supplies = $request["supplies"];
        for($i = 0; $i < count($supplies); $i++)
        {
            // Save provided article one by one
            $supply = array(
                "article_id"        => $supplies[$i]["article_id"],
                "color_id"        => $supplies[$i]["color_id"],
                "supplier_id"        => $supplies[$i]["supplier_id"],
                "quantity"        => $supplies[$i]["quantity"],
                "price_gave"        => $supplies[$i]["price_gave"],
                "date"        => now(),
                "storage_id"        => 1,
                "user_id"        => 1,
                "liaison_id"        => $liaison->id
            );
            $supply = Storage_supplier::create($supply);

            if($supply)
            {
                // Add the provided article to the container(Stock) of a specific storage

                $container = Container::where([
                    ["storage_id", $supply->storage_id],
                    ["article_id", $supply->article_id],
                    ["color_id", $supply->color_id]
                ])->first();

                if($container){
                    $container->quantity = $container->quantity + $supply->quantity;
                    $container->save();
                }else{
                    $container = array(
                        "article_id"        => $supply->article_id,
                        "storage_id"        => $supply->storage_id,
                        "color_id"          => $supply->color_id,
                        "quantity"          => $supply->quantity
                    );
                    Container::create($container);
                }
                $meta['message'] = "Supply saved successful";
            }
        }

        return response()->json([
            'meta' => $meta,
            'data' => $liaison->name
        ]);
    }

    /**
     * Display details of liaison(Reference)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplies = Storage_supplier::where("liaison_id", $id)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supplies'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $supplies
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
