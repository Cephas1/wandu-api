<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Spendtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpendtypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spendtypes = Spendtype::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of spendTypes'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $spendtypes
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
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'SpendType saved successful'
        ];

        if($validation->fails()){

            $meta['status']['message'] = 'Validation error';
            $meta['message'] = $validation->errors();

            return response()->json([
                'meta'  => $meta
            ]);
        }

        $data = [
            'name'      => $request['name']
        ];

        $storage = Spendtype::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $storage
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
        $spendtype = Spendtype::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "SpendType's details"
        ];
        if($spendtype == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $spendtype
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
            'message'   => 'SpendType deleted successful'
        ];

        $supplier = Spendtype::find($id);

        if($supplier->deleted_at == null){
            $supplier->deleted_at = now();
            $supplier->save();
        }else{
            $meta['message'] = 'SpendType already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }
}
