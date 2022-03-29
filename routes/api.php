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

    Route::get('role', 'Api\RoleController@index');
    Route::post('role', 'Api\RoleController@store');
    Route::get('role/{id_role}', 'Api\RoleController@show');
    Route::put('role/{id_role}', 'Api\RoleController@update');
    Route::delete('role/{id_role}', 'Api\RoleController@destroy');

    Route::get('detailshift', 'Api\DetailShiftController@index');
    Route::post('detailshift', 'Api\DetailShiftController@store');
    Route::get('detailshift/{id_detail_shift}', 'Api\DetailShiftController@show');
    Route::put('detailshift/{id_detail_shift}', 'Api\DetailShiftController@update');
    Route::delete('detailshift/{id_detail_shift}', 'Api\DetailShiftController@destroy');

    Route::get('jadwal', 'Api\JadwalController@index');
    Route::post('jadwal', 'Api\JadwalController@store');
    Route::get('jadwal/{id_jadwal}', 'Api\JadwalController@show');
    Route::put('jadwal/{id_jadwal}', 'Api\JadwalController@update');
    Route::delete('jadwal/{id_jadwal}', 'Api\JadwalController@destroy');

    Route::get('mitra', 'Api\MitraController@index');
    Route::post('mitra', 'Api\MitraController@store');
    Route::get('mitra/{id_mitra}', 'Api\MitraController@show');
    Route::put('mitra/{id_mitra}', 'Api\MitraController@update');
    Route::delete('mitra/{id_mitra}', 'Api\MitraController@destroy');
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    
// });

