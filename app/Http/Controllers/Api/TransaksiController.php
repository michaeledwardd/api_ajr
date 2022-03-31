<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    //Method untuk menampilkan semua data product (READ)
    public function index(){
        $transaksis = Transaksi::with(['Mobil', 'Driver', 'Customer', 'Pegawai', 'Promo'])->get(); //Mengambil semua data Transaksi

        if(count($transaksis) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksis
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    //Method untuk menampilkan 1 data Transaksi (SEARCH)
    public function show($id_transaksi){
        $transaksis = Transaksi::find($id_transaksi); //Mencari data Transaksi berdasarkan id

        if(!is_null($transaksis)){
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksis
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    //Method untuk menambah 1 data Transaksi baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'id_customer' => 'required|numeric',
            'id_mobil' => 'required|numeric',
            'id_pegawai' => 'required|numeric',
            'id_driver' => 'required|numeric',
            'id_promo' => 'required|numeric',
            'tgl_transaksi' => 'required|date_format:Y-m-d',
            'tgl_pinjam' => 'required|date_format:Y-m-d',
            'tgl_kembali' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'tgl_selesai_pinjam' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'jenis_peminjaman' => 'required|regex:/^[\pL\s\-]+$/u',
            'cek_terlambat' =>'required',
            'total_denda' => 'required|numeric',
            'total_biaya_pinjam' => 'required|numeric',
            'biaya_denda' => 'required|numeric',
            'total_sewa_driver' => 'required|numeric',
            'bukti_bayar' => 'required',
            'subtotal_all' => 'required|numeric',
            'status_transaksi' => 'required|regex:/^[\pL\s\-]+$/u',
            'metode_bayar' => 'required|regex:/^[\pL\s\-]+$/u',
            'rating_perform_driver' => 'required|numeric',
            'rating_perform_ajr' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $count= DB::table('driver')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');

        $Transaksi = Transaksi::create([
            'id_transaksi'=>'TRN'.$datenow.$id_generate,
            'id_mobil'=>$request->id_mobil,
            '',
        ]);

        return response([
            'message' => 'Add Transaksi Success',
            'data' => $Transaksi
        ], 200); //Return message data Transaksi baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id_transaksi){
        $Transaksi = Transaksi::find($id_transaksi); //Mencari data product berdasarkan id

        if(is_null($Transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Transaksi tidak ditemukan

        if($Transaksi->delete()){
            return response([
                'message' => 'Delete Transaksi Success',
                'data' => $Transaksi
            ], 200);
        } //Return message saat berhasil menghapus data Transaksi

        return response([
            'message' => 'Delete Transaksi Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data Transaksi (UPDATE)
    public function update(Request $request, $id_transaksi){
        $Transaksi = Transaksi::find($id_transaksi); //Mencari data Transaksi berdasarkan id

        if(is_null($Transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Transaksi tidak ditemukan

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

        $Transaksi->hari_kerja = $updateData['hari_kerja']; 
        $Transaksi->jenis_shift = $updateData['jenis_shift']; 
        $Transaksi->jam_mulai = $updateData['jam_mulai']; 
        $Transaksi->jam_selesai = $updateData['jam_selesai']; 

        if($Transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $Transaksi
            ], 200);
        } //Return data Transaksi yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null
        ], 400);
    }
}
