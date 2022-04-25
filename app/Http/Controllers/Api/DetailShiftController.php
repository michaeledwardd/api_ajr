<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Valid_detail_shiftation\Rule;
use Validator;
use App\Models\DetailShift;
use Illuminate\Support\Facades\DB;

class DetailShiftController extends Controller
{
    //Method untuk menampilkan semua data detail shift (READ)
    public function index(){
        $detailshifts = DB::table('detail_shift')
        ->join('jadwal', 'jadwal.id_jadwal', '=', 'detail_shift.id_jadwal')
        ->join('pegawai', 'pegawai.id_pegawai', '=', 'detail_shift.id_pegawai')
        ->join('role','role.id_role','=','pegawai.id_role')
        ->select('id_detail_shift','pegawai.id_pegawai','jadwal.id_jadwal','hari_kerja','jenis_shift','nama_pegawai','nama_role')
        ->orderBy('hari_kerja','desc')->orderBy('jenis_shift','asc')
        ->get(); //Mengambil semua data detail shift

        if(count($detailshifts) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailshifts
            ], 200);
        } //Return data semua detail shift dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data detail shift kosong
    }

    //Method untuk menampilkan 1 data detail shift (SEARCH)
    public function show($id_detail_shift){
        $detailshifts = DetailShift::find($id_detail_shift); //Mencari data detail shift berdasarkan id_detail_shift

        if(!is_null($detailshifts)){
            return response([
                'message' => 'Retrieve DetailShift Success',
                'data' => $detailshifts
            ], 200);
        } //Return data semua detail shift dalam bentuk JSON

        return response([
            'message' => 'DetailShift Not Found',
            'data' => null
        ], 400); //Return message data detail shift kosong
    }

    public function store(Request $request, $id_pegawai){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'id_jadwal' => 'required|numeric',
            'id_pegawai' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $hitungshift = DetailShift::select('id_pegawai')->where('id_pegawai',$id_pegawai)->count();

        if($hitungshift == 6){
            return response([
                'message' => 'Shift sudah melebihi batas',
            ], 400);
        }

        $DetailShift = DetailShift::create($storeData);

        return response([
            'message' => 'Add Detail_Shift Success',
            'data' => $DetailShift
        ], 200); //Return message data Detail_Shift baru dalam bentuk JSON
    }
    

    //Method untuk menghapus 1 data detail shift (DELETE)
    public function destroy($id_detail_shift){
        $DetailShift = DetailShift::find($id_detail_shift); //Mencari data detail shift berdasarkan id_detail_shift

        if(is_null($DetailShift)){
            return response([
                'message' => 'DetailShift Not Found',
                'date' => null
            ], 404);
        } //Return message saat data detail shift tid_detail_shiftak ditemukan

        if($DetailShift->delete()){
            return response([
                'message' => 'Delete DetailShift Success',
                'data' => $DetailShift
            ], 200);
        } //Return message saat berhasil menghapus data detail shift

        return response([
            'message' => 'Delete DetailShift Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data detail shift (UPDATE)
    public function update(Request $request, $id_detail_shift){
        $DetailShift = DetailShift::find($id_detail_shift); //Mencari data detail shift berdasarkan id_detail_shift

        if(is_null($DetailShift)){
            return response([
                'message' => 'DetailShift Not Found',
                'data' => null
            ], 404);
        } //Return message saat data detail shift tid_detail_shiftak ditemukan

        $updateData = $request->all();
        $valid_detail_shift = Validator::make($updateData, [
            'id_jadwal' => 'required|numeric',
            'id_pegawai' => 'required|numeric'
        ]); //Membuat rule valid_detail_shiftasi input

        if($valid_detail_shift->fails()){
            return response(['message' => $valid_detail_shift->errors()], 400); //Return error invalid_detail_shift input
        }

        $DetailShift->id_jadwal = $updateData['id_jadwal']; //Edit Nama Kelas
        $DetailShift->id_pegawai = $updateData['id_pegawai']; //Edit Kode

        if($DetailShift->save()){
            return response([
                'message' => 'Update DetailShift Success',
                'data' => $DetailShift
            ], 200);
        } //Return data detail shift yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update DetailShift Failed',
            'data' => null
        ], 400);
    }
}
