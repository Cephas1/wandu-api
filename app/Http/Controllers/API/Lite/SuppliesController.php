<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Color;
use App\Models\Container;
use App\Models\Liaison;
use App\Models\Shop_storage;
use App\Models\Storage_supplier;
use App\Models\Supplier;
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
        $liaisons = Liaison::where("provides", 1)->orderBy('created_at', 'desc')->get(['id', 'name', 'date', 'time', 'user_id', 'supplier_id'])->load("supplier", "user");

        $id = [];
        foreach($liaisons as $liaison){
            $id[] = $liaison->id;
        }
        $supplies = Storage_supplier::whereIn('liaison_id', $id)->get()->load('article');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supply liaisons'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => ['liaisons' => $liaisons, 'supplies' => $supplies]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Article::orderBy("name", "asc")->get()->load('rubrique');
        $suppliers = Supplier::orderBy("name", "asc")->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of storage containers'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => ['products' => $products, 'providers' => $suppliers]
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

        $supplies = $request["supplies"];
        $meta_data = $request["meta_data"];

        // Create the reference of supplies
        $liaison = array(
            "name"          => "SUP" . rand(1, 99) . now()->dayOfYear,
            "number"        => rand(1, 99999999999),
            "provides"      =>1,
            "storage_id"    => $meta_data["storage_id"],
            "supplier_id"   => $meta_data["supplier_id"],
            "user_id"   => $meta_data["user_id"],
            "date"          => date('Y-m-d'),
            "time"          => date('H:i:s')
        );
        $liaison = Liaison::create($liaison);

        for($i = 0; $i < count($supplies); $i++)
        {
            // save supply
            $supply = array(
                "liaison_id"        => $liaison->id,
                "article_id"        => $supplies[$i]["article_id"],
                "color_id"        => 0,
                "quantity"        => $supplies[$i]["quantity"],
                "price_gave"        => $supplies[$i]["price_gave"],
                "supplier_id"        => $meta_data["supplier_id"],
                "storage_id"        => $meta_data["storage_id"],
                "user_id"        => $meta_data["user_id"],
                "date"        => date('Y-m-d'),
                "time"        => date('H:i:s')
            );
            $supply = Storage_supplier::create($supply);

            if($supply)
            {
                // Add the provided article to the container(Stock) of a specific storage
                $container = Container::where([
                    ["storage_id", $supply->storage_id],
                    ["article_id", $supply->article_id],
                    ["color_id", 0]
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
                
                $article = Article::find($supply->article_id);
                $article->price_1 = $supplies[$i]['price_gave'];
                $article->price_2 = $supplies[$i]['price_vente'];
                $article->mb = $supplies[$i]['marge_brute'];

                $article->save();

                // Create the reference of deliverance (Storage to Shop)
                $ref = array(
                    "name"          => "DE" . rand(1, 99) . now()->dayOfYear,
                    "number"        => rand(1, 99999999999),
                    "deliverances"  => 1,
                    "storage_id"    => $meta_data["storage_id"],
                    "shop_id"       => $meta_data["shop_id"],
                    "user_id"   => $meta_data["user_id"],
                    "date"          => date("Y-m-d"),
                    "time"          => date('H:i:s')
                );
                $ref = Liaison::create($ref);

                // save deliverance
                $deliverance = array(
                    'article_id'        => $supply->article_id,
                    'color_id'          => $supply->color_id,
                    'quantity'          => $supply->quantity,
                    'liaison_id'        => $ref->id,
                    'storage_id'        => $meta_data["storage_id"],
                    'shop_id'           => $meta_data["shop_id"],
                    'user_id'           => $meta_data["user_id"],
                    "date"              => date('Y-m-d'),
                    "time"              => date('H:i:s')
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
    
                // Increment the specific container of shop
                $shop_container = Container::where([
                    ["shop_id", $deliverance->shop_id],
                    ["article_id", $deliverance->article_id],
                    ["color_id", $deliverance->color_id]
                ])->first();
    
                if($shop_container){
                    $shop_container->quantity = $shop_container->quantity + $deliverance->quantity;
                    $shop_container->save();
                }else{
                    $new_shop_container = array(
                        "article_id"    => $deliverance->article_id,
                        "color_id"      => $deliverance->color_id,
                        "shop_id"       => $deliverance->shop_id,
                        "quantity"      => $deliverance->quantity
                    );
                    Container::create($new_shop_container);
                }
                $meta['message'] = "Supply saved successful";
            }
        }

        return response()->json([
            'meta' => $meta,
            'data' => $liaison
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
