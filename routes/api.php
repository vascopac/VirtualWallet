<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('wallets', 'WalletControllerAPI@index');
Route::post('login', 'LoginControllerAPI@login');
Route::post('register', 'UserControllerAPI@store');
Route::middleware('auth:api')->post('logout',
    'LoginControllerAPI@logout');
Route::middleware('auth:api')->put('users/{id}',
    'UserControllerAPI@update');
Route::middleware('auth:api')->post('photo', 
    'UserControllerAPI@updatePhoto');
Route::middleware('auth:api')->get('users/me', 
    'UserControllerAPI@show');
Route::middleware('auth:api')->get('movements/{id}', 
    'MovementControllerAPI@index');



//Route::get('departments', 'DepartmentControllerAPI@index');
//Route::get('users', 'UserControllerAPI@index');
//Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
//Route::get('users/{id}', 'UserControllerAPI@show');
//Route::post('users', 'UserControllerAPI@store');
//Route::put('users/{id}', 'UserControllerAPI@update');
//Route::delete('users/{id}', 'UserControllerAPI@destroy');


