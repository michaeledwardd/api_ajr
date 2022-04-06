<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DriverController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $drivers = Driver::all(); //Mengambil semua data Driver

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
            'password' => 'required',
            'foto_driver' => 'required',
            'status_tersedia' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'is_aktif' => 'required',
            'biaya_sewa_driver' => 'required|numeric',
            'no_telp' => 'required|numeric',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'rerata_rating' => 'required|numeric',
            'mahir_inggris' => 'required',
            'upload_sim' => 'required',
            'upload_bebas_napza' => 'required',
            'upload_sehat_jiwa' => 'required',
            'upload_sehat_jasmani' => 'required',
            'upload_skck' => 'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $count= DB::table('driver')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');

        $driver = Driver::create([
            'id_driver'=>'DRV-'.$datenow.$id_generate,
            'nama_driver'=>$request->nama_driver,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'alamat'=>$request->alamat,
            'email_driver'=>$request->email_driver,
            'password'=>$request->password,
            'foto_driver'=>$request->foto_driver,
            'status_tersedia'=>$request->status_tersedia,
            'status_berkas'=>$request->status_berkas,
            'is_aktif'=>$request->is_aktif,
            'biaya_sewa_driver'=>$request->biaya_sewa_driver,
            'no_telp'=>$request->no_telp,
            'tgl_lahir'=>$request->tgl_lahir,
            'rerata_rating'=>$request->rerata_rating,
            'mahir_inggris' =>$request->mahir_inggris,
            'upload_sim' =>$request->upload_sim,
            'upload_bebas_napza' =>$request->upload_bebas_napza,
            'upload_sehat_jiwa' =>$request->upload_sehat_jiwa,
            'upload_sehat_jasmani' =>$request->upload_sehat_jasmani,
            'upload_skck' =>$request->upload_skck
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
            'foto_driver' => 'required',
            'status_tersedia' => 'required|regex:/^[\pL\s\-]+$/u',
            'status_berkas' => 'required|regex:/^[\pL\s\-]+$/u',
            'is_aktif' => 'required',
            'biaya_sewa_driver' => 'required|numeric',
            'no_telp' => 'required|numeric',
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'rerata_rating' => 'required|numeric',
            'mahir_inggris' => 'required',
            'upload_sim' => 'required',
            'upload_bebas_napza' => 'required',
            'upload_sehat_jiwa' => 'required',
            'upload_sehat_jasmani' => 'required',
            'upload_skck' => 'required'
            
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Driver->nama_driver = $updateData['nama_driver']; 
        $Driver->jenis_kelamin = $updateData['jenis_kelamin'];
        $Driver->alamat = $updateData['alamat'];
        $Driver->email_driver = $updateData['email_driver'];
        $Driver->password = $updateData['password'];
        $Driver->foto_driver = $updateData['foto_driver'];
        $Driver->status_tersedia = $updateData['status_tersedia'];
        $Driver->status_berkas = $updateData['status_berkas'];
        $Driver->is_aktif = $updateData['is_aktif'];
        $Driver->biaya_sewa_driver = $updateData['biaya_sewa_driver'];
        $Driver->no_telp = $updateData['no_telp'];
        $Driver->tgl_lahir = $updateData['tgl_lahir'];
        $Driver->rerata_rating = $updateData['rerata_rating'];
        $Driver->mahir_inggris = $updateData['mahir_inggris'];
        $Driver->upload_sim = $updateData['is_aktif'];
        $Driver->upload_bebas_napza = $updateData['upload_bebas_napza'];
        $Driver->upload_sehat_jiwa = $updateData['upload_sehat_jiwa'];
        $Driver->upload_sehat_jasmani = $updateData['upload_sehat_jasmani'];
        $Driver->upload_skck = $updateData['upload_skck'];

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
