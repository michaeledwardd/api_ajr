<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Promo;

class PromoController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $promo = Promo::all(); //Mengambil semua data promo

        if(count($promo) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promo
            ], 200);
        } //Return data semua promo dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data promo kosong
    }

    public function showbyStatus(){
        $promo = Promo::where('status_promo','aktif')->get(); //Mengambil semua data promo

        if(count($promo) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promo
            ], 200);
        } //Return data semua promo dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data promo kosong
    }

    //Method untuk menampilkan 1 data promo (SEARCH)
    public function show($id_promo){
        $promo = Promo::find($id_promo); //Mencari data promo berdasarkan id

        if(!is_null($promo)){
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        } //Return data semua promo dalam bentuk JSON

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 400); //Return message data promo kosong
    }

    //Method untuk menambah 1 data promo baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'kode_promo' => 'required|unique:promo|regex:/^[\pL\s\-]+$/u',
            'jenis_promo' => 'required|regex:/^[\pL\s\-]+$/u',
            'jumlah_potongan' => 'required|numeric',
            'keterangan' => 'required',
            'status_promo' => 'required'
        ],
        ['jumlah_potongan.numeric' => 'Inputan harus dalam bentuk angka',
        'kode_promo.unique' => 'Kode Promo sudah pernah digunakan',
        'jenis_promo.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain',
        'kode_promo.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain']); //Membuat rule validasi input

        if(is_null($request->kode_promo)
        || is_null($request->jenis_promo)
        || is_null($request->jumlah_potongan)
        || is_null($request->keterangan)
        || is_null($request->status_promo)){
            return response(['message' => 'Inputan tidak boleh kosong'], 400);
        }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Promo = Promo::create($storeData);

        return response([
            'message' => 'Add Promo Success',
            'data' => $Promo
        ], 200); //Return message data promo baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_promo){
        $Promo = Promo::find($id_promo); //Mencari data product berdasarkan id

        if(is_null($Promo)){
            return response([
                'message' => 'Promo Not Found',
                'date' => null
            ], 404);
        } //Return message saat data promo tidak ditemukan

        if($Promo->delete()){
            return response([
                'message' => 'Delete Promo Success',
                'data' => $Promo
            ], 200);
        } //Return message saat berhasil menghapus data promo

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data promo (UPDATE)
    public function update(Request $request, $id_promo){
        $Promo = Promo::find($id_promo); //Mencari data promo berdasarkan id

        if(is_null($Promo)){
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404);
        } //Return message saat data promo tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'kode_promo' => 'required|regex:/^[\pL\s\-]+$/u',
            'jenis_promo' => 'required|regex:/^[\pL\s\-]+$/u',
            'jumlah_potongan' => 'required|numeric',
            'keterangan' => 'required',
            'status_promo' => 'required'
        ],
        [
            'jumlah_potongan.numeric' => 'Inputan harus dalam bentuk angka',
            'jenis_promo.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain',
            'kode_promo.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain'
        ]); //Membuat rule validasi input

        if(is_null($request->kode_promo)
        || is_null($request->jenis_promo)
        || is_null($request->jumlah_potongan)
        || is_null($request->keterangan)
        || is_null($request->status_promo)){
            return response(['message' => 'Inputan tidak boleh kosong'], 400);
        }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Promo->kode_promo = $updateData['kode_promo']; 
        $Promo->jenis_promo = $updateData['jenis_promo'];
        $Promo->jumlah_potongan = $updateData['jumlah_potongan']; 
        $Promo->keterangan = $updateData['keterangan'];
        $Promo->status_promo = $updateData['status_promo'];

        if($Promo->save()){
            return response([
                'message' => 'Update Promo Success',
                'data' => $Promo
            ], 200);
        } //Return data promo yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Promo Failed',
            'data' => null
        ], 400);
    }
}
