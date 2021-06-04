<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basket;
use Illuminate\Support\Facades\Auth;

class BasketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Basket::where([['user_id', Auth::id()],['canceled_at', null], ['bought_at', null]])->get();

        return view('products.index', compact('articles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $basket = array(
            'user_id'           => Auth::id(),
            'article_id'        => $request['article_id'],
            'quantity'          => 1
        );

        Basket::create($basket);

        return redirect()->back();
    }

    /**
     * Remove a resource in basket.
     *
     * @param  int $product_id
     * @return \Illuminate\Http\Response
     */
    public function remove(int $product_id)
    {
        $article = Basket::where([['user_id', Auth::id()], ['article_id', $product_id],['canceled_at', null], ['bought_at', null]])->first();

        $article->canceled_at = date('Y-m-d');
        $article->save();

        return redirect()->back();
    }

    /**
     * Remove all resources in basket.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAll()
    {
        $articles = Basket::where([['user_id', Auth::id()], ['canceled_at', null], ['bought_at', null]])->get();

        foreach($articles as $article)
        {
            $article->canceled_at = date('Y-m-d');
            $article->save();
        }

        return redirect()->back();
    }
}
