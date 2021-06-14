<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Spend;
use App\Models\Article_shop;
use App\Models\Shop_storage;
use App\Models\Container;
use App\Models\Liaison;
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

    public function cashier($shop_id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Cashier dashboard'
        ];

        $date_fin = date('Y-m-d', strtotime('+1 days'));
        $date_debut = date('Y-m-d', strtotime('-6 days'));

        $liaisons = Liaison::where([['shop_id', $shop_id], ['purchases', 1]])
            ->whereBetween('created_at', [$date_debut, $date_fin])
            ->orderBy('created_at', 'desc')
            ->get()
            ->load('user');
        $liaisons = $liaisons->groupBy('date');

        $purchases = Article_shop::where('shop_id', $shop_id)
            ->whereBetween('date', [$date_debut, $date_fin])
            ->orderBy('created_at', 'desc')
            ->get()
            ->load('user', 'article', 'color', 'liaison');

        return response()->json([
            'meta' => $meta,
            'data' => ['liaisons' => $liaisons, 'purchases' => $purchases]
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
        $purchases = $purchases->groupBy('date');

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
            'phone'       => 'required|string|max:30'
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

    /**
         * Store the picture
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */

         public function storePicture(Request $request, $id) {

            $meta = [
                'status' => [
                    'code'  => 200,
                    'message'   => 'OK'
                ],
                'message'   => "Error file"
            ];

            $file = $request->file("image");

            if($file != null){

                $shop = Shop::find($id);

                $image = $shop->id.'.'.$file->getClientOriginalExtension();

                if (!file_exists(public_path('images\shops'))) {
                    mkdir(public_path('images\shops'));
                }

                $file->move(public_path('images\shops'), $image);
                $shop->image = "images\shops\\".$image;
                $saved = $shop->save();

                if($saved){
                    $meta['message'] = "File saved";
                }
            }

            return response()->json([
                'meta' => $meta
            ]);
         }
}
