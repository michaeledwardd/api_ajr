<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    ///Method untuk menampilkan semua data product (READ)
    public function index(){
        $pegawais = Pegawai::with(['Role'])->get(); //Mengambil semua data Pegawai

        if(count($pegawais) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200);
        } //Return data semua Pegawai dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Pegawai kosong
    }

    public function showbyStatus()
    {
        $pegawais = Pegawai::where('is_aktif',1)->get(); //Mengambil semua data Pegawai

        if(count($pegawais) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200);
        } //Return data semua Pegawai dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Pegawai kosong
    }

    //Method untuk menampilkan 1 data Pegawai (SEARCH)
    public function show($id_pegawai){
        $pegawais = Pegawai::find($id_pegawai); //Mencari data Pegawai berdasarkan id
        
        if(!is_null($pegawais)){
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawais
            ], 200);
        } //Return data semua Pegawai dalam bentuk JSON

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 400); //Return message data Pegawai kosong
    }

    //Method untuk menambah 1 data Pegawai baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'id_role' => 'required|numeric',
            'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
            'foto_pegawai' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'email' => 'required|unique:Pegawai|email:rfc,dns',
            'password' => 'required',
            'is_aktif' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Pegawai = Pegawai::create($storeData);

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $Pegawai
        ], 200); //Return message data Pegawai baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_pegawai){
        $Pegawai = Pegawai::find($id_pegawai); //Mencari data product berdasarkan id

        if(is_null($Pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Pegawai tidak ditemukan

        if($Pegawai->delete()){
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $Pegawai
            ], 200);
        } //Return message saat berhasil menghapus data Pegawai

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Pegawai (UPDATE)
    public function update(Request $request, $id_pegawai){
        $Pegawai = Pegawai::find($id_pegawai); //Mencari data Pegawai berdasarkan id

        if(is_null($Pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Pegawai tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_role' => 'required|numeric',
            'nama_pegawai' => 'required|regex:/^[\pL\s\-]+$/u',
            'foto_pegawai' => 'required',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
            'is_aktif' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Pegawai->id_role = $updateData['id_role']; 
        $Pegawai->nama_pegawai = $updateData['nama_pegawai']; 
        $Pegawai->foto_pegawai = $updateData['foto_pegawai']; 
        $Pegawai->tgl_lahir = $updateData['tgl_lahir'];
        $Pegawai->jenis_kelamin = $updateData['jenis_kelamin'];
        $Pegawai->alamat = $updateData['alamat'];
        $Pegawai->email = $updateData['email'];
        $Pegawai->password = $updateData['password'];
        $Pegawai->is_aktif = $updateData['is_aktif'];

        if($Pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $Pegawai
            ], 200);
        } //Return data Pegawai yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null
        ], 400);
    }
}
