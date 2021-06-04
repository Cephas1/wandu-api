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
        $users = User::where([['actif', 1], ['deleted_at', '=', null]])->get()->load('rule');
        
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
    public function show($id){
        
        $user = User::find($id);
        
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'User connected'
        ];

        return response()->json([
            'meta' => $meta,
            'data' => $user
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($id)
    {
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Password reset"
        ];

        $user = User::find($id);

        if($user){

            $user->password = Hash::make(1234);
            $user->save();

            return response()->json([
                'meta' => $meta
            ]);
        }
    }

    /**
     * Store the picture
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

     public function storePicture(Request $request, $id) {
         
        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => "Error file"
        ];

        $file = $request->file("image");

        if($file != null){

            $user = User::find($id);

            $image = $user->id.'.'.$file->getClientOriginalExtension();

            if (!file_exists(public_path('images\users'))) {
                mkdir(public_path('images\users'));
            }

            $file->move(public_path('images\users'), $image);
            $user->image = "images\users\\".$image;
            $saved = $user->save();

            if($saved){
                $meta['message'] = "File saved";
            }
        }
            
        return response()->json([
            'meta' => $meta
        ]);
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
