<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Basket;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('deleted_at','=', null)->limit(5)->get();

        return view('products.index', compact('articles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        $in = Basket::where([['user_id', Auth::id()], ['article_id', $article->id],['canceled_at', null], ['bought_at', null]])->first();

        $in_basket = 0;
        if($in){
            $in_basket = 1;
        }

        return view('products.show', compact('article', 'in_basket'));
    }
}
