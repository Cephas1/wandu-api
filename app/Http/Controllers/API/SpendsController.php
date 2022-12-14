<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Spend;
use Illuminate\Http\Request;

class SpendsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spends = Spend::where('date', date('y-m-d'))->orderBy('time', 'desc')->get()->load('spendtype','shop');

        $meta = [
          'status' => [
              'code' => 200,
              'message' => 'OK'
          ],
            'message' => 'List of spends'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $spends
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_spends($id)
    {
        $spends = Spend::where([['date', date('y-m-d')], ["shop_id", $id]])->orderBy('time', 'desc')->get()->load('spendtype','shop');

        $meta = [
          'status' => [
              'code' => 200,
              'message' => 'OK'
          ],
            'message' => 'List of spends'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $spends
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storage_spends($id)
    {
        $spends = Spend::where([['date', date('y-m-d')], ["storage_id", $id]])->orderBy('time', 'desc')->get()->load('spendtype','shop');

        $meta = [
          'status' => [
              'code' => 200,
              'message' => 'OK'
          ],
            'message' => 'List of spends'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $spends
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
                'code' => 200,
                'message' => 'OK'
            ],
            'message' => 'Failure request'
        ];

        $data = array(
            'description' => $request['description'],
            'price'       => $request['price'],
            'spendtype_id'=> $request['spendtype_id'],
            'shop_id'     => $request['shop_id'],
            'user_id'     => $request['user_id'],
            'date'        => date('y-m-d'),
            'time'        => date('H:i:s')
        );
        $spend = Spend::create($data);

        if($spend){
            $meta['message'] = 'Success request';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $spend
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
