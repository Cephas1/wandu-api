<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Liaison;
use Illuminate\Http\Request;

class RefferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRefStorage($id)
    {
        $deliverance_liaisons = Liaison::where([["deliverances","=", 1],["storage_id", "=", $id]])->orderBy('created_at', 'desc')->get()->load("shop");

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of deliverance shop liaisons'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $deliverance_liaisons
        ]);
    }
}
