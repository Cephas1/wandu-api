<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        $purchases = Purchase::all();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        $validation = Validator::make($request->all(),[
            'client_id'       => 'required|integer',
            'article_id'       => 'required|integer',
            'quantity'       => 'required|numeric',
            'price'       => 'required|integer'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Purchase saved successful'
        ];

        if($validation->fails()){

            $meta['status']['message'] = 'Validation error';
            $meta['message'] = $validation->errors();

            return response()->json([
                'meta'  => $meta
            ]);
        }

        $data = [
            'client_id'      => $request['client_id'],
            'article_id'      => $request['article_id'],
            'user_id'      => 1,
            'quantity'     => $request['quantity'],
            'price'     => $request['price']
        ];

        $purchase = Purchase::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $purchase
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
        $purchase = Purchase::find($id);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Purchase's details"
        ];
        if($purchase == null){
            $meta['message'] = "No data corresponded";
        }

        return response()->json([
            'meta' => $meta,
            'data' => $purchase
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
