<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Mobil;
use Illuminate\Support\Facades\DB;

class MobilController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $mobils = Mobil::all(); //Mengambil semua data Mobil

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


    public function showWithMitra(){
        $mobils = DB::table('mobil')
        ->join('mitra','mobil.id_mitra','=','mitra.id_mitra')
        ->select('mobil.*','nama_mitra','nomor_telepon')
        ->get(); //Mengambil semua data Mobil

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

    public function showbyStatus(){
        $mobils = Mobil::where('status_ketersediaan','tersedia')->get(); //Mengambil semua data Mobil

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
            'volume_bagasi' => 'required|numeric',
            'fasilitas' => 'required',
            'kategori_aset' => 'regex:/^[\pL\s\-]+$/u',
            'status_ketersediaan' => 'regex:/^[\pL\s\-]+$/u',
            'plat_nomor' => 'required|unique:Mobil',
            'foto_mobil' => 'required|max:1024|mimes:jpg,png,jpeg|image',
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

        if(($request->id_mitra)===NULL){
            $request->kategori_aset = sprintf("Perusahaan");
            $request->status_ketersediaan = sprintf("tidak tersedia");
        }
        else{
            $request->kategori_aset = sprintf("Mitra");
            $request->status_ketersediaan = sprintf("tersedia");
        }
        
        $fotoMobil = $request->foto_mobil->store('img_mobil',['disk'=>'public']);

        $Mobil = Mobil::create([
            'id_mitra'=>$request->id_mitra,
            'nama_mobil'=>$request->nama_mobil,
            'jenis_transmisi'=>$request->jenis_transmisi,
            'bahan_bakar'=>$request->bahan_bakar,
            'warna'=>$request->warna,
            'volume_bagasi'=>$request->volume_bagasi,
            'fasilitas'=>$request->fasilitas,
            'kategori_aset'=>$request->kategori_aset,
            'status_ketersediaan'=>$request->status_ketersediaan,
            'plat_nomor'=>$request->plat_nomor,
            'foto_mobil'=>$fotoMobil,
            'tipe_mobil'=>$request->tipe_mobil,
            'kapasitas'=>$request->kapasitas,
            'biaya_sewa'=>$request->biaya_sewa,
            'last_service'=>$request->last_service,
            'awal_kontrak'=>$request->awal_kontrak,
            'akhir_kontrak'=>$request->akhir_kontrak,
            'nomor_stnk'=>$request->nomor_stnk
        ]);

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
            'volume_bagasi' => 'required|numeric',
            'fasilitas' => 'required',
            'kategori_aset' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_ketersediaan' => 'required|regex:/^[\pL\s\-]+$/u',
            'plat_nomor' => 'required',
            'foto_mobil' => 'max:1024|mimes:jpg,png,jpeg|image',
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
        if(isset($request->foto_mobil)){
            $fotoMobil = $request->foto_mobil->store('img_mobil',['disk'=>'public']);
            $Mobil->foto_mobil = $fotoMobil;
        }
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
