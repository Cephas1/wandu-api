<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article_shop;
use App\Models\Storage_supplier;
use App\Models\Shop_storage;

class DashboardController extends Controller
{
    public function admin($date_debut, $date_fin){

        // $current_year = date('Y');
        // $current_month = date('m');
        // $date_debut = $current_year . '-' . $current_month . '-01';
        // $date_fin = date('Y-m-d');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Dashboard admin'
        ];

        $purchases = Article_shop::whereBetween('date', [$date_debut, $date_fin])->get();

        //les 10 articles les plus vendues
        $articles = $purchases->groupBy('article.name');

        $by_article = [];
        foreach($articles as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_article[$key] = $sum;
        }
        asort($by_article);
        $by_article = array_slice($by_article, 0, 10);

        //les 10 couleurs les plus vendues
        $colors = $purchases->groupBy('color.name');

        $by_color = [];
        foreach($colors as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_color[$key] = $sum;
        }
        asort($by_color);
        $by_color = array_slice($by_color, 0, 10);

        //les 10 categories d'articles les plus vendues
        $categories = $purchases->groupBy('article.category.name');

        $by_category = [];
        foreach($categories as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_category[$key] = $sum;
        }
        asort($by_category);
        $by_category = array_slice($by_category, 0, 10);


        //les entrees/sorties
        $entrees = Storage_supplier::whereBetween('date', [$date_debut, $date_fin])->get();
        $entrees_sum = 0;
        foreach($entrees as $entree){
            $entrees_sum = $entrees_sum + ($entree->quantity * $entree->price_gave);
        }

        $sorties = Article_shop::whereBetween('date', [$date_debut, $date_fin])->get();
        $sorties_sum = 0;
        foreach($sorties as $sortie){
            $sorties_sum = $sorties_sum + ($sortie->quantity * $sortie->price_got);
        }

        $entrees_sorties = ['entrees'=> $entrees_sum, 'sorties'=> $sorties_sum];

        //la courbe des recettes
        $days = $purchases->groupBy('date');
        $recettes = [];
        foreach($days as $key => $values){
            $day_sum = 0;
            foreach ($values as $value) {
                $day_sum = $day_sum + ($value->quantity * $value->price_got);
            }
            $recettes[$key] = $day_sum;
        }

        $data = [
            'recettes'      => $recettes,
            'entrees_sorties' => $entrees_sorties,
            'by_article'       => $by_article,
            'by_color'       => $by_color,
            'by_category'       => $by_category
        ];

        return response()->json(['meta' => $meta, 'data' => $data]);
    }


    public function shop($shop_id, $date_debut, $date_fin){

        // $current_year = date('Y');
        // $current_month = date('m');
        // $date_debut = $current_year . '-' . $current_month . '-01';
        // $date_fin = date('Y-m-d');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Dashbord admin'
        ];

        $purchases = Article_shop::where('shop_id', $shop_id)->whereBetween('date', [$date_debut, $date_fin])->get();

        //les 10 articles les plus vendues
        $articles = $purchases->groupBy('article.name');

        $by_article = [];
        foreach($articles as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_article[$key] = $sum;
        }
        asort($by_article);
        $by_article = array_slice($by_article, 0, 10);

        //les 10 couleurs les plus vendues
        $colors = $purchases->groupBy('color.name');

        $by_color = [];
        foreach($colors as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_color[$key] = $sum;
        }
        asort($by_color);
        $by_color = array_slice($by_color, 0, 10);

        //les 10 categories d'articles les plus vendues
        $categories = $purchases->groupBy('article.category.name');

        $by_category = [];
        foreach($categories as $key => $values){
            $sum = 0;
            foreach($values as $value){
                $sum = $sum + $value->quantity;
            }
            $by_category[$key] = $sum;
        }
        asort($by_category);
        $by_category = array_slice($by_category, 0, 10);


        //les entrees/sorties
        $entrees = Shop_storage::where('shop_id', $shop_id)->whereBetween('date', [$date_debut, $date_fin])->get();
        $entrees_sum = 0;
        foreach($entrees as $entree){
            $entrees_sum = $entrees_sum + ($entree->quantity * $entree->article->price_1);
        }

        $sorties = Article_shop::where('shop_id', $shop_id)->whereBetween('date', [$date_debut, $date_fin])->get();
        $sorties_sum = 0;
        foreach($sorties as $sortie){
            $sorties_sum = $sorties_sum + ($sortie->quantity * $sortie->price_got);
        }

        $entrees_sorties = ['entrees'=> $entrees_sum, 'sorties'=> $sorties_sum];

        //la courbe des recettes
        $days = $purchases->groupBy('date');
        $recettes = [];
        foreach($days as $key => $values){
            $day_sum = 0;
            foreach ($values as $value) {
                $day_sum = $day_sum + ($value->quantity * $value->price_got);
            }
            $recettes[$key] = $day_sum;
        }

        $data = [
            'recettes'      => $recettes,
            'entrees_sorties' => $entrees_sorties,
            'by_article'       => $by_article,
            'by_color'       => $by_color,
            'by_category'       => $by_category
        ];

        return response()->json(['meta' => $meta, 'data' => $data]);
    }
}
