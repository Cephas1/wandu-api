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
        $baskets = Basket::where([['user_id', Auth::id()],['canceled_at', null], ['bought_at', null]])->get();

        return view('basket', compact('baskets'));
    }

    /**
     * Store a the command resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCommand(Request $request)
    {
        $baskets = Basket::where([['user_id', Auth::id()],['canceled_at', null], ['bought_at', null]])->get();

        $removed = 0;
        foreach($baskets as $basket)
        {
            $basket->bought_at = date('Y-m-d');
            $basket->montant = $basket->quantity * $basket->article->price_4;
            $basket->save();

            $removed = 1;
        }

        return response()->json($removed);
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

        $removed = 0;
        if($article){
            $article->canceled_at = date('Y-m-d');
            $article->save();
            $removed = 1;
        }

        return response()->json($removed);
    }

    /**
     * Remove all resources in basket.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAll()
    {
        $articles = Basket::where([['user_id', Auth::id()], ['canceled_at', null], ['bought_at', null]])->get();

        $removed = 0;
        foreach($articles as $article)
        {
            $article->canceled_at = date('Y-m-d');
            $article->save();
            $removed = 1;
        }

        return response()->json($removed);
    }
}
