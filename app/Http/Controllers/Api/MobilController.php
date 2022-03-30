<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mobil;

class MobilController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $mobils = Mobil::with(['Mitra'])->get(); //Mengambil semua data Mobil

        if(count($mobils) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobils
            ], 200);
        } //Return data semua Mobil dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Mobil kosong
    }

    //Method untuk menampilkan 1 data Mobil (SEARCH)
    public function show($id_mobil){
        $mobils = Mobil::find($id_mobil); //Mencari data Mobil berdasarkan id

        if(!is_null($mobils)){
            return response([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobils
            ], 200);
        } //Return data semua Mobil dalam bentuk JSON

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 400); //Return message data Mobil kosong
    }

    //Method untuk menambah 1 data Mobil baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'id_mitra' => 'numeric',
            'nama_mobil' => 'required|regex:/^[\pL\s\-]+$/u',
            'jenis_transmisi' => 'required|regex:/^[\pL\s\-]+$/u',
            'bahan_bakar' => 'required|regex:/^[\pL\s\-]+$/u',
            'warna' => 'required|regex:/^[\pL\s\-]+$/u',
            'volume_bagasi' => 'requied|numeric',
            'fasilitas' => 'required|regex:/^[\pL\s\-]+$/u',
            'kategori_aset' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_ketersediaan' => 'required|regex:/^[\pL\s\-]+$/u',
            'plat_nomor' => 'required|unique:Mobil',
            'foto_mobil' => 'required',
            'tipe_mobil' => 'required',
            'kapasitas' => 'required|numeric',
            'biaya_sewa' => 'required|numeric',
            'last_service' => 'required|date_format:Y-m-d',
            'awal_kontrak' => 'required|date_format:Y-m-d',
            'akhir_kontrak' => 'required|date_format:Y-m-d',
            'nomor_stnk' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Mobil = Mobil::create($storeData);

        return response([
            'message' => 'Add Mobil Success',
            'data' => $Mobil
        ], 200); //Return message data Mobil baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_mobil){
        $Mobil = Mobil::find($id_mobil); //Mencari data product berdasarkan id

        if(is_null($Mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Mobil tidak ditemukan

        if($Mobil->delete()){
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $Mobil
            ], 200);
        } //Return message saat berhasil menghapus data Mobil

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Mobil (UPDATE)
    public function update(Request $request, $id_mobil){
        $Mobil = Mobil::find($id_mobil); //Mencari data Mobil berdasarkan id

        if(is_null($Mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Mobil tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_mitra' => 'numeric',
            'nama_mobil' => 'required|regex:/^[\pL\s\-]+$/u',
            'jenis_transmisi' => 'required|regex:/^[\pL\s\-]+$/u',
            'bahan_bakar' => 'required|regex:/^[\pL\s\-]+$/u',
            'warna' => 'required|regex:/^[\pL\s\-]+$/u',
            'volume_bagasi' => 'requied|numeric',
            'fasilitas' => 'required|regex:/^[\pL\s\-]+$/u',
            'kategori_aset' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_ketersediaan' => 'required|regex:/^[\pL\s\-]+$/u',
            'plat_nomor' => 'required|unique:Mobil',
            'foto_mobil' => 'required',
            'tipe_mobil' => 'required',
            'kapasitas' => 'required|numeric',
            'biaya_sewa' => 'required|numeric',
            'last_service' => 'required|date_format:Y-m-d',
            'awal_kontrak' => 'required|date_format:Y-m-d',
            'akhir_kontrak' => 'required|date_format:Y-m-d',
            'nomor_stnk' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Mobil->id_mitra = $updateData['id_mitra']; 
        $Mobil->nama_mobil = $updateData['nama_mobil']; 
        $Mobil->jenis_transmisi = $updateData['jenis_transmisi']; 
        $Mobil->bahan_bakar = $updateData['bahan_bakar']; 
        $Mobil->warna = $updateData['warna']; 
        $Mobil->volume_bagasi = $updateData['volume_bagasi']; 
        $Mobil->fasilitas = $updateData['fasilitas']; 
        $Mobil->kategori_aset = $updateData['kategori_aset'];
        $Mobil->status_ketersediaan = $updateData['status_ketersediaan']; 
        $Mobil->plat_nomor = $updateData['plat_nomor']; 
        $Mobil->foto_mobil = $updateData['foto_mobil']; 
        $Mobil->tipe_mobil = $updateData['tipe_mobil'];  
        $Mobil->kapasitas = $updateData['kapasitas']; 
        $Mobil->biaya_sewa = $updateData['biaya_sewa']; 
        $Mobil->last_service = $updateData['last_service']; 
        $Mobil->awal_kontrak = $updateData['awal_kontrak'];
        $Mobil->akhir_kontrak = $updateData['akhir_kontrak']; 
        $Mobil->nomor_stnk = $updateData['nomor_stnk'];  

        if($Mobil->save()){
            return response([
                'message' => 'Update Mobil Success',
                'data' => $Mobil
            ], 200);
        } //Return data Mobil yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Mobil Failed',
            'data' => null
        ], 400);
    }
}
