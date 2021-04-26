<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\Storage_supplier;
use App\Models\Shop_storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoragesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storages = Storage::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of storages'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $storages
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
            'message'   => 'Storage saved successful'
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

        $storage = Storage::create($data);

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
        $storage = Storage::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Storage's details"
        ];
        if($storage == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $storage
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
            'message'   => 'Storage deleted successful'
        ];

        $storage = Storage::find($id);

        if($storage->deleted_at == null){
            $storage->deleted_at = now();
            $storage->save();
        }else{
            $meta['message'] = 'Storage already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }

    public function dashboard($id){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Storage dashboard'
        ];

        //$storage_id = 6;

        $storage = Storage::select(['id' ,'name', 'location', 'phone', 'email'])->firstWhere('id',$id);

        $approvisionnements = Storage_supplier::where('storage_id', $id)->orderBy('date', 'desc')->get();
        $approvisionnements = $approvisionnements->groupBy('date');

        $livraisons = Shop_storage::where('storage_id', $id)->orderBy('date', 'desc')->get()->load('Shop', 'User');
        $livraisons = $livraisons->groupBy('date');

        $days_supplies = [];
        foreach($approvisionnements as $key => $values){
            $temp = explode(' ', $key);
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $days_supplies[$temp[0]] = $sum;
        }

        $days_livraisons = [];
        foreach($livraisons as $key => $values){
            $temp = explode(' ', $key);
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $days_livraisons[$temp[0]] = $sum;
        }

        return response()->json([
            'meta' => $meta,
            'data' => [
                'storage' => $storage,
                'approvisionnements'    => [$days_supplies],
                'livraisons'    => [$days_livraisons]
            ]
        ]);
    }
}
