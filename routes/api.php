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
Route::middleware('auth:api')->group(function (){
    Route::post('logout',
        'LoginControllerAPI@logout');
    Route::put('users/{id}',
        'UserControllerAPI@update');
    Route::post('photo', 
        'UserControllerAPI@updatePhoto');
    Route::get('users/me', 
        'UserControllerAPI@show');
    Route::get('movements/{id}', 
        'MovementControllerAPI@index');
    Route::patch('user/{id}/edit/password',
        'UserControllerAPI@editPassword');
    Route::get('users',
        'UserControllerAPI@index')->middleware('isAdmin');
    Route::get('categories',
        'CategoryControllerAPI@index');
    Route::put('movements/{id}',
        'MovementControllerAPI@update');
    Route::put('users/admin/{id}',
        'UserControllerAPI@updateAdmin');
    Route::post('users/add',
        'UserControllerAPI@add')->middleware('isAdmin');
    Route::delete('users/{id}',
        'UserControllerAPI@destroy')->middleware('isAdmin');
    Route::patch('users/deactivate',
        'UserControllerAPI@deactivate')->middleware('isAdmin');
    Route::patch('users/reactivate',
        'UserControllerAPI@reactivate')->middleware('isAdmin');
    Route::post('movements', 
        'MovementControllerAPI@store');
    Route::post('movements/income', 
        'MovementControllerAPI@newIncome');
    Route::post('wallet/email',
        'WalletControllerAPI@walletAssociated');
});
