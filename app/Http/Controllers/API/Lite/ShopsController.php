<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller; 
use App\Models\Spend;
use App\Models\Article_shop;
use App\Models\Liaison;
use App\Models\Reglement;
use App\Models\Container;
use App\Models\Fond;
use Illuminate\Http\Request;

class ShopsController extends Controller
{

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
                ["color_id", 0],
                ["quantity", ">", 0]
            ])->get()->load('lot');

            $qte = 0;
            foreach($container as $value){
                $qte = $qte + $value->quantity;
            }

            if($qte >= $purchases[$i]["quantity"] ) { 

                $qte = $purchases[$i]["quantity"];
                foreach($container as $value){
                    if($value->quantity - $qte >= 0){

                        $purchase = array(
                            "article_id"        => $purchases[$i]["article_id"],
                            "color_id"        => 0,
                            "quantity"        => $qte,
                            "price_gave"        => $value->lot->price_gave,
                            "price_got"        => $purchases[$i]["price_got"],
                            "shop_id"        => $meta_data["shop_id"],
                            "user_id"        => $meta_data["user_id"],
                            "liaison_id"        => $liaison->id,
                            'dette'     => 0,
                            "date"        => date('Y-m-d'),
                            "time"        => date('H:i:s')
                        );
                        $purchase = Article_shop::create($purchase);

                        $value->quantity = $value->quantity - $qte;
                        $value->save();

                        break;
                    }else{

                        $purchase = array(
                            "article_id"        => $purchases[$i]["article_id"],
                            "color_id"        => 0,
                            "quantity"        => $value->quantity,
                            "price_gave"        => $value->lot->price_gave,
                            "price_got"        => $purchases[$i]["price_got"],
                            "shop_id"        => $meta_data["shop_id"],
                            "user_id"        => $meta_data["user_id"],
                            "liaison_id"        => $liaison->id,
                            'dette'     => 0,
                            "date"        => date('Y-m-d'),
                            "time"        => date('H:i:s')
                        );
                        $purchase = Article_shop::create($purchase);

                        $qte = $qte - $value->quantity;

                        $value->quantity = 0;
                        $value->save();
                    }
                }

            }else $meta['message'] = "L'article n'est pas dans notre stock";
        }

        return response()->json([
            'meta' => $meta,
            'data' => $liaison
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dette(Request $request)
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
            "name"      => "DET" . rand(1, 99) . now()->dayOfYear,
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
                ["color_id", 0]
            ])->first();

            if($container) {

                $purchase = array(
                    "article_id"        => $purchases[$i]["article_id"],
                    "color_id"        => 0,
                    "quantity"        => $purchases[$i]["quantity"],
                    "price_got"        => $purchases[$i]["price_got"],
                    "shop_id"        => $meta_data["shop_id"],
                    "user_id"        => $meta_data["user_id"],
                    'dette'     => 1,
                    "client_id"   => $meta_data["client_id"],
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

    public function cashier($shop_id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Cashier dashboard'
        ];

        $purchases = Article_shop::where('shop_id', $shop_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->load('user', 'article');

        $g = $purchases->groupBy('date');

        $dates = [];
        foreach($g as $k => $v){
            $dates[] = $k;
        }

        return response()->json([
            'meta' => $meta,
            'data' => ['dates' => $dates, 'purchases' => $purchases]
        ]);
    }

    public function getDette($shop_id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'All dettes'
        ];

        $purchases = Article_shop::where('shop_id', $shop_id)
            ->where('dette', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->load('user', 'client', 'liaison', 'article');

        $g = $purchases->groupBy('liaison_id');

        $liaisons = [];
        $dettes = [];
        foreach($g as $k => $v){
            $liaisons[] = $k;

            $montant = 0;
            foreach ($v as $value) {
                $montant = $montant + ($value->quantity * $value->price_got);
            }

            $dettes[] = [
                'ref_id' => $k, 
                'ref' => $v[0]->liaison->name, 
                'date' => $v[0]->date, 
                'client' => $v[0]->client->name, 
                'montant' => $montant, 
                'user' => $v[0]->user->name
            ];
        }

        $reglements = Reglement::whereIn('liaison_id', $liaisons)->get()->load('liaison', 'user');

        return response()->json([
            'meta' => $meta,
            'data' => ['dettes' => $dettes, 'dette_details' => $purchases, 'paiements' => $reglements]
        ]);
    }

    public function storeReglementDette(Request $request){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Reglement saved successful'
        ];

        $response = Reglement::create([
            'date'  => date('Y-m-d'),
            'time'  => date('H:i:s'),
            'percu'   => $request['percu'],
            'user_id'   => $request['user_id'],
            'liaison_id'    => $request['liaison_id']
        ]);

        $r = 0;

        if($response) $r = 1;

        return response()->json([
            'meta' => $meta,
            'data' => $r
        ]);
    }

    public function fond(int $shop_id, int $mnt){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Fond saved successful'
        ];

        $response = Fond::create([
            'date'  => date('Y-m-d'),
            'time'  => date('H:i:s'),
            'montant'   => $mnt,
            'shop_id'   => $shop_id
        ]);

        $r = 0;

        if($response) $r = 1;

        return response()->json([
            'meta' => $meta,
            'data' => $r
        ]);
    }

    public function isFond(int $shop_id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'There is Fond'
        ];

        $fond = Fond::where('date', date('Y-m-d'))->where('shop_id', $shop_id)->first();

        $r = 0;
        if ($fond) $r = 1;

        return response()->json([
            'meta' => $meta,
            'data' => $r
        ]);
    }
}
