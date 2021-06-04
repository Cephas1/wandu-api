<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function create(Request $request)
    {

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'User saved successful'
        ];

        $validation = Validator::make($request->all(),[
            'name'       => 'required|string|max:255'
        ]);

        if($validation->fails()){

            $meta['status']['message'] = 'Validation error';
            $meta['message'] = $validation->errors();

            return response()->json([
                'meta'  => $meta
            ]);
        }

        $user = User::create([
            'name' => $request['name'],
            'rule_id'   => $request['rule_id'],
            'email' => Str::random(60) . '@wandu.cg',
            'password' => Hash::make(1234),
            'actif' => 1,
            'api_token' => Str::random(60),
        ]);

        return response()->json([
            'meta' => $meta,
            'user'  => $user
        ]);
    }
}
