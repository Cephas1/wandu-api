<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storage_supplier;
use App\Models\Article_shop;
use App\Models\Shop_storage;

class ComptaController extends Controller
{
    public function inventaire(Request $request){

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
        $livraisons = [];
        $ventes = [];

        if($shop_id){

            $entrees = Shop_storage::where('shop_id', $shop_id)->whereBetween('date', [$date_debut, $date_fin])->get()->load('article', 'color');
            $entrees = $entrees->groupBy(['article.name', 'color.name']);

            $sorties = Article_shop::where('shop_id', $shop_id)->whereBetween('date', [$date_debut, $date_fin])->get()->load('article', 'color');
            $sorties = $sorties->groupBy(['article.name', 'color.name']);

        }else {

            $entrees = Shop_storage::whereBetween('date', [$date_debut, $date_fin])->get()->load('article', 'color');
            $entrees = $entrees->groupBy(['article.name', 'color.name']);

            $sorties = Article_shop::whereBetween('date', [$date_debut, $date_fin])->get()->load('article', 'color');
            $sorties = $sorties->groupBy(['article.name', 'color.name']);

        }
        
        foreach($entrees as $product_name => $values){

            $livraison = [];

            foreach($values as $color_name => $val){
                $details = [];
                $quantity = 0;

                foreach($val as $v){
                    $details['prix_gros'] = $v->article->price_1;
                    $quantity = $quantity + $v->quantity;
                }
                $details['quantity'] = $quantity;


                $livraison[] = [$color_name => $details];
            }

            $livraisons[] = [$product_name => $livraison];
        }

        foreach($sorties as $product_name => $values){

            $vente = [];

            foreach($values as $color_name => $val){

                $details = [];
                $total_vente = 0;
                $quantity = 0;

                foreach($val as $v){
                    $details['prix_gros'] = $v->article->price_1;
                    $quantity = $quantity + $v->quantity;
                    $total_vente = $total_vente + ($v->price_got * $v->quantity);
                }
                $details['quantity'] = $quantity;
                $details['total_achat'] = $total_vente;


                $vente[] = [$color_name => $details];
            }

            $ventes[] = [$product_name => $vente];
        }


        return response()->json([
            'meta' => $meta,
            'data' => ['entrees' => $livraisons, 'sorties' => $ventes]
        ]);
    }
}
