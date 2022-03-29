<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mitra;

class MitraController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $mitras = Mitra::all(); //Mengambil semua data Mitra

        if(count($mitras) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mitras
            ], 200);
        } //Return data semua Mitra dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Mitra kosong
    }

    //Method untuk menampilkan 1 data Mitra (SEARCH)
    public function show($id_mitra){
        $mitras = Mitra::find($id_mitra); //Mencari data Mitra berdasarkan id

        if(!is_null($mitras)){
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitras
            ], 200);
        } //Return data semua Mitra dalam bentuk JSON

        return response([
            'message' => 'Mitra Not Found',
            'data' => null
        ], 400); //Return message data Mitra kosong
    }

    //Method untuk menambah 1 data Mitra baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'nama_mitra' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'nomor_ktp' => 'required|numeric|max:16',
            'nomor_telepon' => 'required|numeric|digits_between:10,13|starts_with:08'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Mitra = Mitra::create($storeData);

        return response([
            'message' => 'Add Mitra Success',
            'data' => $Mitra
        ], 200); //Return message data Mitra baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_mitra){
        $Mitra = Mitra::find($id_mitra); //Mencari data product berdasarkan id

        if(is_null($Mitra)){
            return response([
                'message' => 'Mitra Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Mitra tidak ditemukan

        if($Mitra->delete()){
            return response([
                'message' => 'Delete Mitra Success',
                'data' => $Mitra
            ], 200);
        } //Return message saat berhasil menghapus data Mitra

        return response([
            'message' => 'Delete Mitra Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Mitra (UPDATE)
    public function update(Request $request, $id_mitra){
        $Mitra = Mitra::find($id_mitra); //Mencari data Mitra berdasarkan id

        if(is_null($Mitra)){
            return response([
                'message' => 'Mitra Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Mitra tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_mitra' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'nomor_ktp' => 'required|numeric|max:16',
            'nomor_telepon' => 'required|numeric|digits_between:10,13|starts_with:08'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Mitra->nama_mitra = $updateData['nama_mitra']; 
        $Mitra->alamat = $updateData['alamat']; 
        $Mitra->nomor_ktp = $updateData['nomor_ktp']; 
        $Mitra->nomor_telepon = $updateData['nomor_telepon']; 

        if($Mitra->save()){
            return response([
                'message' => 'Update Mitra Success',
                'data' => $Mitra
            ], 200);
        } //Return data Mitra yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Mitra Failed',
            'data' => null
        ], 400);
    }
}
