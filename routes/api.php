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
    Route::get('promo', 'Api\PromoController@index');
    Route::get('promobystatus', 'Api\PromoController@showbyStatus');
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
    Route::get('mitrabystatus', 'Api\MitraController@showbyStatus');
    Route::post('mitra', 'Api\MitraController@store');
    Route::get('mitra/{id_mitra}', 'Api\MitraController@show');
    Route::put('mitra/{id_mitra}', 'Api\MitraController@update');
    Route::delete('mitra/{id_mitra}', 'Api\MitraController@destroy');

    Route::get('pegawai', 'Api\PegawaiController@index');
    Route::get('pegawaibystatus', 'Api\PegawaiController@showbyStatus');
    Route::post('pegawai', 'Api\PegawaiController@store');
    Route::get('pegawai/{id_mitra}', 'Api\PegawaiController@show');
    Route::put('pegawai/{id_mitra}', 'Api\PegawaiController@update');
    Route::delete('pegawai/{id_mitra}', 'Api\PegawaiController@destroy');

    Route::get('customer', 'Api\CustomerController@index');
    Route::post('customer', 'Api\CustomerController@store');
    Route::get('customer/{id_customer}', 'Api\CustomerController@show');
    Route::put('customer/{id_customer}', 'Api\CustomerController@update');
    Route::delete('customer/{id_customer}', 'Api\CustomerController@destroy');

    Route::get('driver', 'Api\DriverController@index');
    Route::get('driverbystatus', 'Api\DriverController@showbyStatus');
    Route::post('driver', 'Api\DriverController@store');
    Route::get('driver/{id_driver}', 'Api\DriverController@show');
    Route::put('driver/{id_driver}', 'Api\DriverController@update');
    Route::delete('driver/{id_driver}', 'Api\DriverController@destroy');

    Route::get('mobil', 'Api\MobilController@index');
    Route::post('mobil', 'Api\MobilController@store');
    Route::get('mobil/{id_mobil}', 'Api\MobilController@show');
    Route::put('mobil/{id_mobil}', 'Api\MobilController@update');
    Route::delete('mobil/{id_mobil}', 'Api\MobilController@destroy');

    Route::get('transaksi', 'Api\TransaksiController@index');
    Route::post('transaksi', 'Api\TransaksiController@store');
    Route::get('transaksi/{id_transaksi}', 'Api\TransaksiController@show');
    Route::put('transaksi/{id_transaksi}', 'Api\TransaksiController@update');
    Route::delete('transaksi/{id_transaksi}', 'Api\TransaksiController@destroy');
    
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
    
// });

