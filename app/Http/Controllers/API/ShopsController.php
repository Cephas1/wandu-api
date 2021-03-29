<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Spend;
use App\Models\Article_shop;
use App\Models\Shop_storage;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of shops'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $shops
        ]);
    }

    public function dashboard($shop_id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Shop dashboard'
        ];

        $shop = Shop::select(['name', 'location'])->firstWhere('id',$shop_id);

        $container = Container::where('shop_id', $shop_id);

        $purchases = Article_shop::where('shop_id', $shop_id)->get();
        $purchases = $purchases->groupBy('dtn');

        $days_sold = [];
        foreach($purchases as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + ($value->price_got * $value->quantity);
            }
            $days_sold[$key] = $sum;
        }

        $spends = Spend::where('shop_id', $shop_id)->get();
        $spends = $spends->groupBy('date');

        $days_spends = [];
        foreach($spends as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->price;
            }
            $days_spends[$key] = $sum;
        }

        $supplies = Shop_storage::where('shop_id', $shop_id)->get();
        $supplies = $supplies->groupBy('date');

        $days_supplies = [];
        foreach($supplies as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->price;
            }
            $days_supplies[$key] = $sum;
        }

        return response()->json([
            'meta' => $meta,
            'data' => ['shop' => $shop,'recettes' => $days_sold,'depenses'=> $days_spends, 'livraisons'=> $days_supplies]
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
        $validation = Validator::make($request->all(),[
            'name'       => 'required|string',
            'location'       => 'required|string',
            'phone'       => 'required|string|max:30',
            'email'       => 'required|string'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Shop saved successful'
        ];

        if($validation->fails()){

            $meta['status']['message'] = 'Validation error';
            $meta['message'] = $validation->errors();

            return response()->json([
                'meta'  => $meta
            ]);
        }

        $data = [
            'name'      => $request['name'],
            'phone'      => $request['phone'],
            'location'     => $request['location'],
            'email'     => $request['email'],
        ];

        $shop = Shop::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $shop
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop = Shop::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Shop's details"
        ];
        if($shop == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $shop
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
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Shop deleted successful'
        ];

        $shop = Shop::find($id);

        if($shop->deleted_at == null){
            $shop->deleted_at = now();
            $shop->save();

        }else{
            $meta['message'] = 'Shop already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }
}