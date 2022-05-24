<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanController extends Controller
{
    //query uts nomor 6
    public function LaporanPenyewaanMobil($tanggalawal, $tanggalakhir)
    {
        $data = DB::select("SELECT tipe_mobil, nama_mobil, COUNT(id_mobil) as jumlah_peminjaman, SUM(subtotal_all) AS pendapatan FROM mobil JOIN transaksi USING(id_mobil) WHERE tgl_transaksi BETWEEN '$tanggalawal' AND '$tanggalakhir'  GROUP BY id_mobil ORDER BY pendapatan DESC");

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

    //query uts nomor 7
    public function LaporanDetailPendapatan($tanggalawal, $tanggalakhir){
        $data = DB::select("SELECT nama_customer, nama_mobil, (case when id_driver is null then 'mobil' else 'mobil + driver' end) as 'jenis_transaksi', count(id_mobil) as jumlah_peminjaman, sum(subtotal_all) as pendapatan from customer join transaksi using(id_customer) join mobil using(id_mobil) where tgl_transaksi between '$tanggalawal' and '$tanggalakhir' group by id_mobil order by pendapatan desc");

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

    //query uts nomor 8
    public function LaporantopDriver($tanggalawal, $tanggalakhir)
    {
        $data = DB::select("SELECT id_driver, nama_driver, COUNT(id_driver) AS jumlah_transaksi FROM driver JOIN transaksi USING(id_driver) WHERE tgl_transaksi BETWEEN '$tanggalawal' AND '$tanggalakhir' GROUP BY (id_driver) ORDER BY (jumlah_transaksi) DESC LIMIT 5");

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

    //query uts nomor 9
    public function TopDriverbyRating($tanggalawal, $tanggalakhir){
        $data = DB::select("SELECT id_driver, nama_driver, count(id_driver) as jumlah_transaksi, (sum(rating_perform_driver)/count(id_driver)) as rerata_rating_drv from driver join transaksi using(id_driver) where tgl_transaksi between '$tanggalawal' and '$tanggalakhir' group by(id_driver)
        order by(jumlah_transaksi) desc limit 5");

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

    //query uts nomor 10
    public function LaporanTopCustomer($tanggalawal, $tanggalakhir){
        $data = DB::select("SELECT nama_customer, count(id_customer) as jumlah_transaksi 
        from transaksi join customer using(id_customer) where tgl_transaksi between '$tanggalawal' and '$tanggalakhir' group by (id_customer) order by (jumlah_transaksi) desc limit 5");

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
        $data = DB::table('transaksi')
        ->select('transaksi.id_transaksi','transaksi.created_at','customer.nama_customer','pegawai.nama_pegawai','driver.nama_driver','driver.biaya_sewa_driver','mobil.biaya_sewa','promo.kode_promo','transaksi.tgl_pinjam','transaksi.tgl_kembali','transaksi.tgl_selesai_pinjam','mobil.nama_mobil','transaksi.total_denda','transaksi.subtotal_all','transaksi.jumlah_diskon','transaksi.total_biaya_pinjam','transaksi.total_sewa_driver')
        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo')
        ->leftjoin('driver', 'transaksi.id_driver', '=' ,'driver.id_driver')
        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
        ->where('id_transaksi','=',$id_transaksi)->get();

        $pdf = PDF::loadview('nota_pdf',['data'=>$data]);

        return $pdf->stream();

        if(count($data) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $data
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 404); //Return message data Transaksi kosong
    }
}
