<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where([['actif', 1], ['deleted_at', '=', null]])->get();
        
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'List of users'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function desactiveOrActiveUser(Request $request, $id)
    {
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Data not found"
        ];

        $user = User::find($id);

        if($user){
            $user->actif = $request['actif'];
            $user->save();

            $meta['message'] = "User's actif value changed";
        }

        return response()->json([
            'meta' => $meta
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $id)
    {
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Password changed"
        ];

        $old_pwd = $request['old_pwd'];
        $new_pwd = $request['new_pwd'];

        $user = User::find($id);

        if(!Hash::check($old_pwd, $user->password)){                

            $meta['message'] = "Old password is wrong";
            return response()->json([
                'meta' => $meta
            ]);

        }else{

            $user->password = Hash::make($new_pwd);
            $user->save();

            return response()->json([
                'meta' => $meta,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
