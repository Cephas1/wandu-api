<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Shop_storage;
use App\Models\Liaison;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function getNotifications(Request $request){
        
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of notifications'
        ];

        $shop_id = $request['shop_id'];
        $storage_id = $request['storage_id'];
        
        if($shop_id){

            $notifications = DB::table('notifications')->join('liaisons', function ($join) {
                $join->on('liaisons.id', '=', 'notifications.liaison_id')
                ->where('liaisons.shop_id', 1)
                ->orderByRaw('notifications.viewed_at DESC')
                ->orderBy('notifications.created_at', 'desc');
            })->get();
            
        }elseif($storage_id){

            $notifications = DB::table('notifications')->join('liaisons', function ($join) {
                $join->on('liaisons.id', '=', 'notifications.liaison_id')
                ->where('liaisons.storage_id', $storage_id)
                ->orderByRaw('notifications.viewed_at DESC')
                ->orderBy('notifications.created_at', 'desc');
            })->get();
        }

        return response()->json([
            'meta' => $meta,
            'data' => $notifications
        ]);

    }

    public function getDetails($id){
        
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'failure request'
        ];

        $notification = Notification::find($id);
        if($notification->type_notification_id == 1){

            $reference = Liaison::find($notification->liaison_id);
            $details = Shop_storage::where('liaison_id', $notification->liaison_id)->get()->load('article', 'color');
            
            $meta['message'] = "Deliverance's details";
        }

        return response()->json([
            'meta' => $meta,
            'data' => ['reference' => $reference, 'details' => $details]
        ]);
    }

    public function confirmed(Request $request){
        
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'Deliverance confirmed'
        ];

        $deliverances_id = $request['deliverances_id'];

        foreach($deliverances_id as $id){

            $deliverance = Shop_storage::find($id);
            

            if($deliverance->confirmed == 0){
                
                // increment of a specific container of shop
                $shop_container = Container::where([
                    ["shop_id", $deliverance->shop_id],
                    ["article_id", $deliverance->article_id],
                    ["color_id", $deliverance->color_id]
                ])->first();

                if($shop_container){
                    $shop_container->quantity = $shop_container->quantity + $deliverance->quantity;
                    $shop_container->save();
                }else{
                    $container = array(
                        "article_id"        => $deliverance->article_id,
                        "shop_id"        => $deliverance->shop_id,
                        "color_id"          => $deliverance->color_id,
                        "quantity"          => $deliverance->quantity
                    );
                    Container::create($container);
                }
                
                $deliverance->confirmed = 1;
                $deliverance->save();
            }
        }

        return response()->json([
            'meta' => $meta
        ]);
    }
}
