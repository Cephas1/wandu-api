<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storage_supplier;
use App\Models\Article_shop;

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


        $entrees = Storage_supplier::whereBetween('date', [$date_debut, $date_fin])->get();
        $entrees = $entrees->groupBy(['article_id', 'color_id']);
        $sorties = Article_shop::whereBetween('date', [$date_debut, $date_fin])->get();
        $sorties = $sorties->groupBy(['article_id', 'color_id']);

        return response()->json([
            'meta' => $meta,
            'data' => ['entrees' => $entrees, 'sorties' => $sorties]
        ]);
    }
}
