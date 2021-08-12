<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Article_shop;
use App\Models\Reglement;
use Illuminate\Http\Request;
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
        $clients = Client::orderBy('name')->get();

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
        $validation = Validator::make($request->all(),[
            'name'       => 'required|string',
            'phone'       => 'required|string|min:7'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Client saved successful'
        ];

        $client = array(
            'name'      => $request['name'],
            'phone'       => $request['phone'],
            'location'   => $request['location'],
            'email'     => $request['email'],
            'num_cni'   => $request['num_cni']
        );

        $saved = Client::create($client);

        $response = 0;
        if($saved) $response = 1;

        return response()->json([
            'meta' => $meta,
            'data' => $response
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
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'No data corresponded'
        ];;

        $client = Client::find($id);

        if ($client){
            $meta['message'] = 'Client\'s details';
            $dettes = Article_shop::orderBy('created_at', 'desc')->where('dette', 1)->where('client_id', $id)->get();
            $dettes = $dettes->groupBy('liaison_id');
    
            $reglements = [];
            foreach($dettes as $key => $values){
                $reglements[] = Reglement::where('liaison_id', $key)->get();
            }
        }

        
        return response()->json([
            'meta' => $meta,
            'data' => ['client' => $client, 'dettes' => $dettes, 'reglements' => $reglements]
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
