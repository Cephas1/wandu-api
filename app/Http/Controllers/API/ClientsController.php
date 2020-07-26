<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of clients'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $clients
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
            'phone'       => 'required|string|max:30',
            'address'       => 'required|string',
            'email'       => 'required|string',
            'image_uri'       => 'nullable|image'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Client saved successful'
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
            'address'     => $request['address'],
            'email'     => $request['email'],
        ];

        $client = Client::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $client
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
        $client = Client::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Client's details"
        ];
        if($client == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $client
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
            'message'   => 'Client deleted successful'
        ];

        $client = Client::find($id);

        if($client->deleted_at == null){
            $client->deleted_at = Carbon::now();
            $client->save();

        }else{
            $meta['message'] = 'Client already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }
}