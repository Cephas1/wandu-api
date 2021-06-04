<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\BadResponseException;

class LoginController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function login(Request $request){

    //     $http = new Client();
    //     $response = $http->post('http://myapi.test/oauth/token', [
    //         'form_params' => [
    //             'grant_type'    => 'password',
    //             'client_id'     => '2',
    //             'client_secret' => 'kzjri0UEWPIEBaPRMzAqwQiXINoBfR9y755B8Oco',
    //             'username'      => $request['username'],
    //             'password'      => $request['password']
    //         ]
    //     ]);

    //     return json_decode((string) $response->getBody(), true);

    // }



    public function getToken(Request $request){

        $meta = [
            'status' => [
                'code'  => 200,
                'message'   => 'OK'
            ],
            'message'   => 'User authenticated'
        ];

        $username = $request['username'];
        $password = $request['password'];

        $user = User::where('name', $username)->get()->first();

        if($user && $user->actif == 1){
            
            $user->load('rule');

            if(!Hash::check($password, $user->password)){

                $meta['message'] = "Bad credentials";
                return response()->json([
                    'meta' => $meta
                ]);

            }else{

                $user->api_token = Str::random(60);
                $user->save();

                return response()->json([
                    'meta' => $meta,
                    'user'  => $user,
                    'access_token'  => $user->api_token
                ]);
            }
        }                

        $meta['message'] = "Bad credentials";
        return response()->json([
            'meta' => $meta
        ]);
    }


}
