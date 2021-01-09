<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article_shop;
use App\Models\Spend;
use Illuminate\Http\Request;

class RapportController extends Controller
{

    public function rapport(int $shop_id, ?string $date = null){

        $date =  $date??date('Y-m-d');
        
        $purchases = Article_shop::where([['dtn', $date],['shop_id', $shop_id]])->orderBy('time', 'desc')->get()->load('article','color');

        $spends = Spend::where([['date', $date],['shop_id', $shop_id]])->orderBy('time', 'desc')->get()->load('spendtype');

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Rapport de la journee'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => ["purchases" => $purchases, "spends" => $spends]
        ]);
    }
}
