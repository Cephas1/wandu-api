<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Article::where('deleted_at','=', null)->get();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of articles'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $products
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
            'description'       => 'required|string|min:10',
            'price_1'       => 'required|integer',
            'price_2'       => 'required|integer',
            'price_3'       => 'required|integer',
            'price_4'       => 'required|integer',
            'category_id'       => 'required|integer',
            'image_uri'       => 'nullable|image'
        ]);

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Article saved successful'
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
            'description'      => $request['description'],
            'price_1'     => $request['price_1'],
            'price_2'     => $request['price_2'],
            'price_3'     => $request['price_3'],
            'price_4'     => $request['price_4'],
            'quantity'     => 0,
            'category_id'     => $request['category_id'],
        ];

        $article = Article::create($data);

        return response()->json([
            'meta' => $meta,
            'data' => $article
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
        $article = Article::where([['deleted_at', null],['id', $id]])->first();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Article's details"
        ];
        if($article == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $article
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
            'message'   => 'Article deleted successful'
        ];

        $article = Article::find($id);

        if($article->deleted_at == null){
            $article->deleted_at = Carbon::now();
            $article->save();

        }else{
            $meta['message'] = 'Article already deleted';
        }

        return response()->json([
            'meta' => $meta,
            'data' => 'id : ' . $id
        ]);
    }
}
