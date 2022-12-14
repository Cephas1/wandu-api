<?php

namespace App\Http\Controllers\API\Lite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storage_supplier;
use App\Models\Container;
use App\Models\Article;
use App\Models\Article_shop;
use App\Models\Shop_storage;

class ComptaController extends Controller
{
    public function shop_inventaire(int $shop_id, string $first_date, string $last_date){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supplies'
        ];

        $inventaires = [];

        // Debut du traitement des livraisons
        $entrees = Storage_supplier::where('storage_id', 2)->whereBetween('date', [$first_date, $last_date])->get()->load('article');
        $entrees = $entrees->groupBy('article.name');
        
        $livraisons = [];
        foreach($entrees as $key => $values){
            
            $quantity = 0;
            $cout = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $cout = $cout + ($value->quantity * $value->price_gave);
            }

            $livraisons[] = ['article' => $key, 'entrees' => $quantity, 'cout' => $cout];
        }
        // Fin du traitement des livraisons

        // Debut du traitement des ventes
        $sorties = Article_shop::where('shop_id', 1)->whereBetween('date', [$first_date, $last_date])->get()->load('article');
        $sorties = $sorties->groupBy('article.name');
        
        $ventes = [];
        foreach($sorties as $key => $values){
            
            $quantity = 0;
            $recette = 0;
            $cout = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $recette = $recette + ($value->quantity * $value->price_got);
                $cout = $cout + ($value->quantity * $value->price_gave);
            }
            $ventes[] = ['article' => $key, 'sorties' => $quantity,'cout' => $cout, 'marge' => $recette - $cout, 'gain' => $recette];
        }
        // Fin du traitement des ventes

        return response()->json([
            'meta' => $meta,
            'data' => ['entrees' => $livraisons, 'sorties' => $ventes]
        ]);
    }
}
