<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function login(Request $request){

        $http = new Client();
        $response = $http->post('http://localhost:8000/oauth/token', [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => 2,
                'client_secret' => 'ThYfkPYVjblrcWX6eniPMrjXYgvytaujjrgNyvEf',
                'username'      => $request['username'],
                'password'      => $request['password']
            ]
        ]); dd($response);

//        try {
//            $response = $http->post('http://localhost:8000/oauth/token', [
//                'form_params' => [
//                    'grant_type'    => 'password',
//                    'client_id'     => 2,
//                    'client_secret' => 'ThYfkPYVjblrcWX6eniPMrjXYgvytaujjrgNyvEf',
//                    'username'      => $request['username'],
//                    'password'      => $request['password']
//                ]
//            ]); //dd($response);
//
//            return response()->json($response->getBody());
//
//        }catch (BadResponseException $e){
//            if($e->getCode() == 404){
//                return response()->json('Invalid request. Please enter a username or a password.', $e->getCode());
//            }elseif($e->getCode() == 401){
//                return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
//            }
//
//            return response()->json('Something went wrong on the server', $e->getCode());
//        }

    }
}
