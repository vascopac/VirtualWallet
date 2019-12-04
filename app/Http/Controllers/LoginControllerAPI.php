<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

define('SERVER_URL', env('PASSPORT_URL'));
define('CLIENT_ID', env('PASSPORT_ID'));
define('CLIENT_SECRET',env('PASSPORT_SECRET'));

class LoginControllerAPI extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(SERVER_URL.'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $request->email,
                'password' => $request->get('password'),
                'scope' => ''
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            //return json_decode((string) $response->getBody(), true);
            $user = User::where('email', $request->email)->get()->first();

            return response()->json([
                'token' => json_decode((string) $response->getBody(), true),
                'user' => new UserResource($user),
                'status' => 200
            ]);
        } else {
            return response()->json(
                ['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
