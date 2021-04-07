<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article_shop;
use App\Models\Storage_supplier;
use App\Models\Shop_storage;
use App\Models\Spend;
use Illuminate\Http\Request;

class RapportController extends Controller
{

    public function rapport(int $shop_id, ?string $date = null){

        $date =  $date??date('Y-m-d');
        
        $purchases = Article_shop::where([['date', $date],['shop_id', $shop_id]])->orderBy('time', 'desc')->get()->load('liaison','user','article','color');

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

    public function rapportSupplies(int $shop_id, ?string $date = null){

        $meta = [
            'status' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'message' => 'Rapport of Supplies'
        ];

        $date =  $date??date('Y-m-d');

        $supplies = Shop_storage::where([['date', $date], ['shop_id', $shop_id]])->orderBy('time', 'desc')->get()->load('storage','liaison','article', 'color', 'user');

        return response()->json([
            'meta' => $meta,
            'data' => ["livraisons" => $supplies]
        ]);
    }

    public function storagesRapport(int $storage_id, ?string $date = null){

        $meta = [
            'status' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'message' => 'Rapport of Provides and Supplies'
        ];

        $date =  $date??date('Y-m-d');

        $provides = Storage_supplier::where([['date', $date],['storage_id', $storage_id]])->orderBy('time', 'desc')->get()->load( 'liaison', 'storage', 'article.category', 'color', 'supplier', 'user');

        $supplies = Shop_storage::where([['date', $date], ['storage_id', $storage_id]])->orderBy('time', 'desc')->get()->load('liaison', 'shop', 'article.category', 'color', 'user');

        return response()->json([
            'meta' => $meta,
            'data' => [['approvisionnements' => $provides], ["livraisons" => $supplies]]
        ]);
    }
}
