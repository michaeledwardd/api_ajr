<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanController extends Controller
{
    public function LaporanPendapatanMobil()
    {
        $data = DB::select("SELECT tipe_mobil, nama_mobil, COUNT(id_mobil) as jumlah_peminjaman, SUM(subtotal_all) AS pendapatan FROM mobil JOIN transaksi USING(id_mobil) WHERE tgl_transaksi BETWEEN '2022-03-01' AND '2022-03-31' GROUP BY id_mobil ORDER BY pendapatan DESC");

        $pdf = PDF::loadview('laporanpendapatanmobil_pdf',['data'=>$data]);

        return $pdf->download('laporan-pendapatan-mobil.pdf');

        if(count($data) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function LaporantopDriver()
    {
        $data = DB::select("SELECT id_driver, nama_driver, COUNT(id_driver) AS jumlah_transaksi FROM driver JOIN transaksi USING(id_driver) WHERE tgl_transaksi BETWEEN '2022-03-01' AND '2022-03-31' GROUP BY (id_driver) ORDER BY (jumlah_transaksi) DESC LIMIT 5");

        $pdf = PDF::loadview('laporantopdriver_pdf',['data'=>$data]);

        return $pdf->download('laporan-top-driver.pdf');

        if(count($data) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }

    public function cetakNotaTransaksi($id_transaksi){
        
    }
}
