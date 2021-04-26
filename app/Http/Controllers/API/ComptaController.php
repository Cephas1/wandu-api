<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storage_supplier;
use App\Models\Container;
use App\Models\Article;
use App\Models\Article_shop;
use App\Models\Shop_storage;

class ComptaController extends Controller
{
    public function shop_inventaire(Request $request){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supplies'
        ];

        $date_debut = $request['date_debut'];
        $date_fin = $request['date_fin'];
        $shop_id = $request['shop_id'];
        $article_id = $request['article_id'];

        $product = Article::select('name', 'price_1', 'price_2', 'price_3', 'price_4')->find($article_id);

        // Debut du traitement des livraisons
        $entrees = Shop_storage::where([['shop_id', $shop_id], ['article_id', $article_id]])->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $entrees = $entrees->groupBy('color.name');
        
        $livraisons = [];
        foreach($entrees as $key => $values){
            
            $quantity = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
            }
            $livraisons[] = ['color' => $key, 'quantity' => $quantity];
        }
        // Fin du traitement des livraisons

        // Debut du traitement des ventes
        $sorties = Article_shop::where([['shop_id', $shop_id], ['article_id', $article_id]])->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $sorties = $sorties->groupBy('color.name');
        
        $ventes = [];
        foreach($sorties as $key => $values){
            
            $quantity = 0;
            $recette = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $recette = $recette + ($value->quantity * $value->price_got);
            }
            $ventes[] = ['color' => $key, 'quantity' => $quantity, 'recette' => $recette];
        }
        // Fin du traitement des ventes

        // Debut du traitement du stock disponible
        $container = Container::where([['shop_id', $shop_id], ['article_id', $article_id]])->get()->load('color');
        
        $stock = [];
        foreach($container as $value){            
            $stock[] = ['color' => $value->color->name, 'quantity' => $value->quantity];
        }
        // Fin du traitement du stock disponible

        return response()->json([
            'meta' => $meta,
            'data' => ['product' => $product, 'entrees' => $livraisons, 'sorties' => $ventes, 'stock' => $stock]
        ]);
    }

    public function storage_inventaire(Request $request){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of supplies'
        ];

        $date_debut = $request['date_debut'];
        $date_fin = $request['date_fin'];
        $storage_id = $request['storage_id'];
        $article_id = $request['article_id'];

        // Debut du traitement des livraisons
        $entrees = Storage_supplier::where([['storage_id', $storage_id], ['article_id', $article_id]])->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $entrees = $entrees->groupBy('color.name');
        
        $livraisons = [];
        foreach($entrees as $key => $values){
            
            $quantity = 0;
            $cout = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $cout = $cout + ($value->quantity * $value->price_gave);
            }
            $livraisons[] = ['color' => $key, 'quantity' => $quantity, 'cout' => $cout];
        }
        // Fin du traitement des livraisons

        // Debut du traitement des approvisionnements des boutiques
        $sorties = Shop_storage::where([['storage_id', $storage_id], ['article_id', $article_id]])->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $sorties = $sorties->groupBy('color.name');
        
        $deliverances = [];
        foreach($sorties as $key => $values){
            
            $quantity = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
            }
            $deliverances[] = ['color' => $key, 'quantity' => $quantity];
        }
        // Fin du traitement des approvisionnements des boutiques

        // Debut du traitement du stock disponible
        $container = Container::where([['storage_id', $storage_id], ['article_id', $article_id]])->get()->load('color');
        
        $stock = [];
        foreach($container as $value){            
            $stock[] = ['color' => $value->color->name, 'quantity' => $value->quantity];
        }
        // Fin du traitement du stock disponible

        return response()->json([
            'meta' => $meta,
            'data' => ['entrees' => $livraisons, 'sorties' => $deliverances, 'stock' => $stock]
        ]);

    }
    
    public function all_inventaire(Request $request){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'All inventory'
        ];

        $date_debut = $request['date_debut'];
        $date_fin = $request['date_fin'];
        $article_id = $request['article_id'];

        // Debut du traitement des livraisons
        $entrees = Storage_supplier::where('article_id', $article_id)->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $entrees = $entrees->groupBy('color.name');
        
        $livraisons = [];
        foreach($entrees as $key => $values){
            
            $quantity = 0;
            $cout = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $cout = $cout + ($value->quantity * $value->price_gave);
            }
            $livraisons[] = ['color' => $key, 'quantity' => $quantity, 'cout' => $cout];
        }
        // Fin du traitement des livraisons

        // Debut du traitement des approvisionnements des boutiques
        $sorties = Article_shop::where('article_id', $article_id)->whereBetween('date', [$date_debut, $date_fin])->get()->load('color');
        $sorties = $sorties->groupBy('color.name');
        
        $ventes = [];
        foreach($sorties as $key => $values){
            
            $quantity = 0;
            $recette = 0;
            foreach($values as $value){
                $quantity = $quantity + $value->quantity;
                $recette = $recette + ($value->quantity * $value->price_got);
            }
            $ventes[] = ['color' => $key, 'quantity' => $quantity, 'recette' => $recette];
        }
        // Fin du traitement des approvisionnements des boutiques

        // Debut du traitement du stock disponible
        $container = Container::where('article_id', $article_id)->get()->load('color');
        
        $stock = [];
        foreach($container as $value){        
            if($value->shop_id != null){
                $value->load('shop');
                $stock[] = ['color' => $value->color->name, 'entity' => $value->shop->name, 'quantity' => $value->quantity];
            }else {
                $value->load('storage');
                $stock[] = ['color' => $value->color->name, 'entity' => $value->storage->name, 'quantity' => $value->quantity];
            }
        }
        // Fin du traitement du stock disponible

        return response()->json([
            'meta' => $meta,
            'data' => ['entrees' => $livraisons, 'sorties' => $ventes, 'stock' => $stock]
        ]);

    }
}
