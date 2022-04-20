<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Api\TransaksiController;

class DriverController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $drivers = Driver::orderBy('created_at','asc')->get(); //Mengambil semua data Driver

        if(count($drivers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        } //Return data semua Driver dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Driver kosong
    }

    public function showstatusAktif(){
        $drivers = Driver::where('is_aktif',1)->get(); //Mengambil semua data Driver

        if(count($drivers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        } //Return data semua Driver dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Driver kosong
    }

    public function showstatusTersedia(){
        $drivers = DB::table('driver')
        ->select('nama_driver','jenis_kelamin','no_telp','biaya_sewa_driver','status_tersedia')
        ->where('status_tersedia','tersedia')
        ->get();
        // $drivers = Driver::where('status_tersedia','tersedia')->get(); //Mengambil semua data Driver

        if(count($drivers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        } //Return data semua Driver dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Driver kosong
    }

    public function show($id_driver){
        $drivers = Driver::find($id_driver); //Mencari data Driver berdasarkan id

        if(!is_null($drivers)){
            return response([
                'message' => 'Retrieve Driver Success',
                'data' => $drivers
            ], 200);
        } //Return data semua Driver dalam bentuk JSON

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 400); //Return message data Driver kosong
    }
    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_driver'=> 'required',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'email_driver' => 'required|email:rfc,dns|unique:Driver',
            'password',
            'foto_driver' => 'required',
            'status_tersedia' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'is_aktif' => 'required',
            'biaya_sewa_driver' => 'required|numeric',
            'no_telp' => 'required|numeric',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'rerata_rating' => 'numeric',
            'mahir_inggris' => 'required',
            'upload_sim' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'upload_bebas_napza' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'upload_sehat_jiwa' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'upload_sehat_jasmani' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'upload_skck' => 'required|max:1024|mimes:jpg,png,jpeg|image'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('driver')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');

        $fotoDriver = $request->foto_driver->store('img_driver',['disk'=>'public']);
        $fotoSIM = $request->upload_sim->store('img_SIM',['disk'=>'public']);
        $fotoBebasNapza = $request->upload_bebas_napza->store('img_bebas_napza',['disk'=>'public']);
        $fotoSehatJiwa = $request->upload_sehat_jiwa->store('img_sehat_jiwa',['disk'=>'public']);
        $fotoSehatJasmani = $request->upload_sehat_jasmani->store('img_sehat_jasmani',['disk'=>'public']);
        $fotoSKCK = $request->upload_skck->store('img_skck',['disk'=>'public']);

        $driver = Driver::create([
            'id_driver'=>'DRV-'.$datenow.$id_generate,
            'nama_driver'=>$request->nama_driver,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'alamat'=>$request->alamat,
            'email_driver'=>$request->email_driver,
            'password'=>bcrypt($request->tgl_lahir),
            'foto_driver'=>$fotoDriver,
            'status_tersedia'=>$request->status_tersedia,
            'status_berkas'=>$request->status_berkas,
            'is_aktif'=>$request->is_aktif,
            'biaya_sewa_driver'=>$request->biaya_sewa_driver,
            'no_telp'=>$request->no_telp,
            'tgl_lahir'=>$request->tgl_lahir,
            // 'rerata_rating'=>$request->rerata_rating,
            'mahir_inggris' =>$request->mahir_inggris,
            'upload_sim' =>$fotoSIM,
            'upload_bebas_napza' =>$fotoBebasNapza,
            'upload_sehat_jiwa' =>$fotoSehatJiwa,
            'upload_sehat_jasmani' =>$fotoSehatJasmani,
            'upload_skck' =>$fotoSKCK,
        ]);
     
        return response([
            'message' => 'Add driver Success',
            'data' => $driver,
        ],200);
    }

    public function destroy($id_driver){
        $Driver = Driver::find($id_driver); //Mencari data product berdasarkan id

        if(is_null($Driver)){
            return response([
                'message' => 'Driver Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Driver tidak ditemukan

        if($Driver->delete()){
            return response([
                'message' => 'Delete Driver Success',
                'data' => $Driver
            ], 200);
        } //Return message saat berhasil menghapus data Driver

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Driver (UPDATE)
    public function update(Request $request, $id_driver){
        $Driver = Driver::find($id_driver); //Mencari data Driver berdasarkan id

        if(is_null($Driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Driver tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_driver'=> 'required',
            'jenis_kelamin' => 'required|regex:/^[\pL\s\-]+$/u',
            'alamat' => 'required',
            'email_driver' => 'required|email:rfc,dns',
            'password' => 'required',
            'foto_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
            'status_tersedia' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'is_aktif' => 'required',
            'biaya_sewa_driver' => 'required|numeric',
            'no_telp' => 'required|numeric',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            // 'rerata_rating',
            'mahir_inggris' => 'required',
            'upload_sim' => 'max:1024|mimes:jpg,png,jpeg|image',
            'upload_bebas_napza' => 'max:1024|mimes:jpg,png,jpeg|image',
            'upload_sehat_jiwa' => 'max:1024|mimes:jpg,png,jpeg|image',
            'upload_sehat_jasmani' => 'max:1024|mimes:jpg,png,jpeg|image',
            'upload_skck' => 'max:1024|mimes:jpg,png,jpeg|image'
            
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }
        $id_driver = $request->id_driver;
        $hitungrerata= DB::select("SELECT SUM(rating_perform_driver) / COUNT(id_driver) AS 'reratabaru' FROM transaksi WHERE id_driver = '$id_driver' ");
        $hasilrerata = array_column($hitungrerata, 'reratabaru');
        $reratafinal =  DB::update("UPDATE driver SET rerata_rating = '$hasilrerata[0]' WHERE id_driver = '$id_driver' ");
        
        $Driver->nama_driver = $updateData['nama_driver']; 
        $Driver->jenis_kelamin = $updateData['jenis_kelamin'];
        $Driver->alamat = $updateData['alamat'];
        $Driver->email_driver = $updateData['email_driver'];
        $Driver->password = bcrypt($updateData['tgl_lahir']);
        if(isset($request->foto_driver)){
            $fotoDriver = $request->foto_driver->store('img_driver',['disk'=>'public']);
            $Driver->foto_driver = $fotoDriver;
        } 
        $Driver->status_tersedia = $updateData['status_tersedia'];
        $Driver->status_berkas = $updateData['status_berkas'];
        $Driver->is_aktif = $updateData['is_aktif'];
        $Driver->biaya_sewa_driver = $updateData['biaya_sewa_driver'];
        $Driver->no_telp = $updateData['no_telp'];
        $Driver->tgl_lahir = $updateData['tgl_lahir'];
        if(isset($request->rerata_rating)){
            $Driver->rerata_rating = $reratafinal;
        }
        $Driver->mahir_inggris = $updateData['mahir_inggris'];
        if(isset($request->upload_sim)){
            $fotoSIM = $request->upload_sim->store('img_SIM',['disk'=>'public']);
            $Driver->upload_sim = $fotoSIM;
        } 
        if(isset($request->upload_bebas_napza)){
            $fotoBebasNapza = $request->upload_bebas_napza->store('img_bebas_napza',['disk'=>'public']);
            $Driver->upload_bebas_napza = $fotoBebasNapza;
        }
        if(isset($request->upload_sehat_jiwa)){
            $fotoSehatJiwa = $request->upload_sehat_jiwa->store('img_sehat_jiwa',['disk'=>'public']);
            $Driver->upload_sehat_jiwa = $fotoSehatJiwa;
        }
        if(isset($request->upload_sehat_jasmani)){
            $fotoSehatJasmani = $request->upload_sehat_jasmani->store('img_sehat_jasmani',['disk'=>'public']);
            $Driver->upload_sehat_jasmani = $fotoSehatJasmani;
        }
        if(isset($request->upload_skck)){
            $fotoSKCK = $request->upload_skck->store('img_skck',['disk'=>'public']);
            $Driver->upload_skck= $fotoSKCK;
        }
        
        if($Driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $Driver
            ], 200);
        } //Return data Driver yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Driver Failed',
            'data' => null
        ], 400);
    }
}
