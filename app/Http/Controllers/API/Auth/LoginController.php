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
        $response = $http->post('http://myapi.test/oauth/token', [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => '2',
                'client_secret' => 'kzjri0UEWPIEBaPRMzAqwQiXINoBfR9y755B8Oco',
                'username'      => $request['username'],
                'password'      => $request['password']
            ]
        ]);

        return json_decode((string) $response->getBody(), true);

    }
}
