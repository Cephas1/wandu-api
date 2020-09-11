<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of categories'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $categories
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
            'unity'       => 'required|string|max:4',
            'code'       => 'required|string|max:10'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Category saved successful'
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
            'code'      => $request['code'],
            'unity'     => $request['unity']
        ];

        $category = Category::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $category
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
        $category = Category::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Category's details"
        ];
        if($category == null){
            $meta['message'] = "No data corresponded";
        }

        return response()->json([
            'meta' => $meta,
            'data' => $category
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
            'message'   => 'Category deleted successful'
        ];

        $category = Category::find($id);

        if($category->deleted_at == null){
            $category->deleted_at = now();
            $category->save();

        }else{
            $meta['message'] = 'Category already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }
}
