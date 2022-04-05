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
        $transaksis = Transaksi::with(['Mobil', 'Driver', 'Customer', 'Pegawai', 'Promo'])->orderBy('created_at','asc')->get(); //Mengambil semua data Transaksi

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
            'id_customer' => 'required',
            'id_mobil' => 'required|numeric',
            'id_pegawai' => 'required|numeric',
            'id_driver',
            'id_promo'=>'numeric',
            'tgl_transaksi' => 'required|date_format:Y-m-d',
            'tgl_pinjam' => 'required|date_format:Y-m-d',
            'tgl_kembali' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'tgl_selesai_pinjam' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'jenis_peminjaman',
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

        $count= DB::table('transaksi')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');

        if(($request->id_driver)===NULL)
        {
            $kode_pinjam = sprintf("02");
            $request->jenis_peminjaman = sprintf("mobil");
        }
        else{
            $kode_pinjam = sprintf("01");
            $request->jenis_peminjaman = sprintf("mobil + driver");
        }

        $Transaksi = Transaksi::create([
            'id_transaksi'=>'TRN'.$datenow.$kode_pinjam.'-'.$id_generate,
            'id_customer'=>$request->id_customer,
            'id_mobil'=>$request->id_mobil,
            'id_pegawai'=>$request->id_pegawai,
            'id_driver'=>$request->id_driver,
            'id_promo'=>$request->id_promo,
            'tgl_transaksi'=>$request->tgl_transaksi,
            'tgl_pinjam'=>$request->tgl_pinjam,
            'tgl_kembali'=>$request->tgl_kembali,
            'tgl_selesai_pinjam'=>$request->tgl_selesai_pinjam,
            'jenis_peminjaman'=>$request->jenis_peminjaman,
            'cek_terlambat'=>$request->cek_terlambat,
            'total_denda'=>$request->total_denda,
            'total_biaya_pinjam'=>$request->total_biaya_pinjam,
            'biaya_denda'=>$request->biaya_denda,
            'total_sewa_driver'=>$request->total_sewa_driver,
            'bukti_bayar'=>$request->bukti_bayar,
            'subtotal_all'=>$request->subtotal_all,
            'status_transaksi'=>$request->status_transaksi,
            'metode_bayar'=>$request->metode_bayar,
            'rating_perform_driver'=>$request->rating_perform_driver,
            'rating_perform_ajr'=>$request->rating_perform_ajr
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
            'id_customer' => 'required',
            'id_mobil' => 'required|numeric',
            'id_pegawai' => 'required|numeric',
            'id_driver',
            'id_promo'=>'numeric',
            'tgl_transaksi' => 'required|date_format:Y-m-d',
            'tgl_pinjam' => 'required|date_format:Y-m-d',
            'tgl_kembali' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'tgl_selesai_pinjam' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'jenis_peminjaman',
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

        $Transaksi->id_customer = $updateData['id_customer']; 
        $Transaksi->id_mobil = $updateData['id_mobil']; 
        $Transaksi->id_pegawai = $updateData['id_pegawai']; 
        $Transaksi->id_driver = $updateData['id_driver'];
        $Transaksi->id_promo = $updateData['id_promo']; 
        $Transaksi->tgl_transaksi = $updateData['tgl_transaksi']; 
        $Transaksi->tgl_pinjam = $updateData['tgl_pinjam']; 
        $Transaksi->tgl_kembali = $updateData['tgl_kembali'];
        $Transaksi->tgl_selesai_pinjam = $updateData['tgl_selesai_pinjam']; 
        $Transaksi->jenis_peminjaman = $updateData['jenis_peminjaman']; 
        $Transaksi->cek_terlambat = $updateData['cek_terlambat']; 
        $Transaksi->total_denda = $updateData['total_denda'];
        $Transaksi->total_biaya_pinjam = $updateData['total_biaya_pinjam']; 
        $Transaksi->biaya_denda = $updateData['biaya_denda']; 
        $Transaksi->total_sewa_driver = $updateData['total_sewa_driver']; 
        $Transaksi->bukti_bayar = $updateData['bukti_bayar'];
        $Transaksi->subtotal_all = $updateData['subtotal_all']; 
        $Transaksi->status_transaksi = $updateData['status_transaksi']; 
        $Transaksi->metode_bayar = $updateData['metode_bayar']; 
        $Transaksi->rating_perform_driver = $updateData['rating_perform_driver']; 
        $Transaksi->rating_perform_ajr = $updateData['rating_perform_ajr']; 

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
