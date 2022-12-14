<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Storage_supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::where('deleted_at','=', null)->orderBy('name', 'asc')->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of suppliers'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $suppliers
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
            'message'   => 'Supplier saved successful'
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

        $storage = Supplier::create($data);

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
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'No data corresponded'
        ];

        $supplier = Supplier::where([['deleted_at', null],['id', $id]])->first();
        $supplies = null;


        if($supplier != null){
            $liaisons = [];
            $meta['message'] = "Supplier's details";

            $provides = Storage_supplier::where('supplier_id', $id)->get()->load('liaison', 'storage', 'user', 'color', 'article');
            $supplies = $provides->groupBy('liaison_id');

            foreach($supplies as $key => $values){
                $liaisons[] = [
                    'liaison_id' => $key,
                    'liaison_name' => $values[0]['liaison']['name'],
                    'liaison_date' => $values[0]['liaison']['date'],
                    'storage' => $values[0]['storage']['name'],
                    'user' => $values[0]['user']['name'],
                ];
            }
        }

        return response()->json([
            'meta' => $meta,
            'data' => [
                'supplier' => $supplier,
                'liaisons'  => $liaisons,
                'supplies' => $provides
            ]
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
            'message'   => 'Supplier deleted successful'
        ];

        $supplier = Supplier::find($id);

        if($supplier->deleted_at == null){
            $supplier->deleted_at = now();
            $supplier->save();
        }else{
            $meta['message'] = 'Supplier already deleted';
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

                $supplier = Supplier::find($id);

                $image = $supplier->id.'.'.$file->getClientOriginalExtension();

                if (!file_exists(public_path('images\suppliers'))) {
                    mkdir(public_path('images\suppliers'));
                }

                $file->move(public_path('images\suppliers'), $image);
                $supplier->image = "images\suppliers\\".$image;
                $saved = $supplier->save();

                if($saved){
                    $meta['message'] = "File saved";
                }
            }

            return response()->json([
                'meta' => $meta
            ]);
         }
}
