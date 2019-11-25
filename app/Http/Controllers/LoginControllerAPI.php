<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(
                ['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'nif' => ['required', 'numeric', 'digits:9'],
            'photo' => ['nullable|mimes:jpeg,bmp,png,jpg']
        ]);

        $path = null;
        if (array_key_exists('photo', $request)) {
            $photo = $request->photo;
            do{
                $path = str_random(32) . '.' . $photo->getClientOriginalExtension();
            }while (count(User::where('photo', $path)->get())>0);
            Storage::disk('public')->putFileAs('profiles', $photo, $path);
        }
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nif' => $request->nif,
            'photo' => $path,
        ]);
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}
