<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Article_shop;
use App\Models\Storage_supplier;
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
        $products = Article::where('deleted_at','=', null)->orderBy('name', 'asc')->get()->load('category');

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
            'description'       => 'nullable|string|min:3',
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
        $article = Article::where([['deleted_at', null],['id', $id]])->first()->load('category');

        $entres = Storage_supplier::where('article_id', $id)->get();
        $livraisons_q = 0;
        $livraisons_c = 0;
        foreach($entres as $entre){
            $livraisons_q = $livraisons_q + $entre->quantity;
            $livraisons_c = $livraisons_c + ($entre->price_gave * $entre->quantity);
        }
        $livraisons = ['provided' => $livraisons_q, 'cost' => $livraisons_c];

        $sorties = Article_shop::where('article_id', $id)->get();
        $ventes_q = 0;
        $ventes_c = 0;
        foreach($sorties as $sortie){
            $ventes_q = $ventes_q + $sortie->quantity;
            $ventes_c = $ventes_c + ($sortie->quantity * $sortie->price_got);
        }
        $ventes = ['sold' => $ventes_q, 'gain' => $ventes_c];

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
            'data' => ['product' => $article, 'entrees' => $livraisons, 'sorties' => $ventes]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function create()
    {
        $categories = Category::all();

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Article's categories"
        ];
        if($categories == null){
            $meta['message'] = 'No data corresponded';
        }

        return response()->json([
            'meta' => $meta,
            'data' => $categories
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
