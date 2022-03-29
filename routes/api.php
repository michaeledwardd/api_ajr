<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('driver','Api\DriverController@store');

    Route::get('promo', 'Api\PromoController@index');
    Route::post('promo', 'Api\PromoController@store');
    Route::get('promo/{id_promo}', 'Api\PromoController@show');
    Route::put('promo/{id_promo}', 'Api\PromoController@update');
    Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    
// });

