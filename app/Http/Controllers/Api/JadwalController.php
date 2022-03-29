<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $jadwals = Jadwal::all(); //Mengambil semua data jadwal

        if(count($jadwals) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwals
            ], 200);
        } //Return data semua jadwal dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data jadwal kosong
    }

    //Method untuk menampilkan 1 data jadwal (SEARCH)
    public function show($id_jadwal){
        $jadwals = Jadwal::find($id_jadwal); //Mencari data jadwal berdasarkan id

        if(!is_null($jadwals)){
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwals
            ], 200);
        } //Return data semua jadwal dalam bentuk JSON

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ], 400); //Return message data jadwal kosong
    }

    //Method untuk menambah 1 data jadwal baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'hari_kerja' => 'required|regex:/^[\pL\s\-]+$/u',
            'jenis_shift' => 'required|numeric',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Jadwal = Jadwal::create($storeData);

        return response([
            'message' => 'Add Jadwal Success',
            'data' => $Jadwal
        ], 200); //Return message data jadwal baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_jadwal){
        $Jadwal = Jadwal::find($id_jadwal); //Mencari data product berdasarkan id

        if(is_null($Jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'date' => null
            ], 404);
        } //Return message saat data jadwal tidak ditemukan

        if($Jadwal->delete()){
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $Jadwal
            ], 200);
        } //Return message saat berhasil menghapus data jadwal

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data jadwal (UPDATE)
    public function update(Request $request, $id_jadwal){
        $Jadwal = Jadwal::find($id_jadwal); //Mencari data jadwal berdasarkan id

        if(is_null($Jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404);
        } //Return message saat data jadwal tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'hari_kerja' => 'required|regex:/^[\pL\s\-]+$/u',
            'jenis_shift' => 'required|numeric',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $Jadwal->hari_kerja = $updateData['hari_kerja']; 
        $Jadwal->jenis_shift = $updateData['jenis_shift']; 
        $Jadwal->jam_mulai = $updateData['jam_mulai']; 
        $Jadwal->jam_selesai = $updateData['jam_selesai']; 

        if($Jadwal->save()){
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $Jadwal
            ], 200);
        } //Return data jadwal yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Jadwal Failed',
            'data' => null
        ], 400);
    }
}
