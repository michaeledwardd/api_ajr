<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Transaksi;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class TransaksiController extends Controller
{
    // Method untuk menampilkan semua data product (READ)
    public function index(){
        $transaksis = Transaksi::all(); //Mengambil semua data Transaksi

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

    public function showdataAll(){
        $transaksis = DB::table('transaksi')
        ->select('id_transaksi','promo.id_promo','driver.id_driver','pegawai.id_pegawai','mobil.id_mobil','customer.id_customer','transaksi.*','jenis_promo','jumlah_potongan','nama_driver','biaya_sewa_driver','nama_customer','nama_pegawai','nama_mobil')
        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo')
        ->leftjoin('driver', 'transaksi.id_driver', '=' ,'driver.id_driver')
        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
        ->orderBy('created_at','asc')->get(); //Mengambil semua data Transaksi

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

    public function showdataAllbyUser($id_customer){
        $transaksis = DB::table('transaksi')
        ->select('id_transaksi','promo.id_promo','driver.id_driver','pegawai.id_pegawai','mobil.id_mobil','customer.id_customer','transaksi.*','jenis_promo','jumlah_potongan','nama_driver','biaya_sewa_driver','nama_customer','nama_pegawai','nama_mobil')
        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo')
        ->leftjoin('driver', 'transaksi.id_driver', '=' ,'driver.id_driver')
        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
        ->where('customer.id_customer','=',$id_customer)
        ->orderBy('created_at','asc')->get(); //Mengambil semua data Transaksi

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
            'id_promo',
            'tgl_transaksi' => 'required|date_format:Y-m-d',
            'tgl_pinjam' => 'required|date_format:Y-m-d',
            'tgl_kembali' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'tgl_selesai_pinjam',
            'jenis_peminjaman',
            'cek_terlambat',
            'total_denda' => 'numeric',
            'total_biaya_pinjam' => 'numeric',
            'biaya_denda' => 'numeric',
            'total_sewa_driver' => 'numeric',
            'bukti_bayar' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'subtotal_all' => 'numeric',
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $count= DB::table('transaksi')->count() +1;
        $id_generate = sprintf("%03d", $count);
        $datenow = Carbon::now()->format('dmy');
        $buktiBayar = $request->bukti_bayar->store('img_bukti_bayar',['disk'=>'public']);

        //bayar mobil dan driver//
        $awal = new DateTime($request->tgl_pinjam);
        $akhir = new DateTime($request->tgl_kembali);
        $diffdays = $awal->diff($akhir);
        if($diffdays->m >= 1){
            $interval = ($diffdays->m) * 30 + $diffdays->d;
        }
        else if($diffdays->y >= 1){
            $interval = ($diffdays->y * 365) + ($diffdays->m * 30) + $diffdays->d ;
        }
        else{
            $interval = $diffdays->d;
        }
        $sewa = mobil::where('id_mobil', $request->id_mobil)->first();
        $harga = $sewa->biaya_sewa;

        //hitung waktu denda
        $waktukembali = new DateTime($request->waktu_kembali);
        $waktumobilmasuk = new DateTime($request->waktu_selesai_pinjam);
        $hasilselisih = $waktukembali->diff($waktumobilmasuk);
        
        //hitung tanggal denda
        $tglkembali = new DateTime($request->tgl_kembali);
        $tglselesaipinjam = new DateTime($request->tgl_selesai_pinjam);
        $selisihtanggal = $tglkembali->diff($tglselesaipinjam);
        if($selisihtanggal->m >= 1){
            $intervaldenda = ($selisihtanggal->m) * 30 + $selisihtanggal->d;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $request->cek_terlambat = 1;
        }
        else if($selisihtanggal->y >= 1){
            $intervaldenda = ($selisihtanggal->y * 365) + ($selisihtanggal->m * 30) + $selisihtanggal->d ;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $request->cek_terlambat = 1;
        }
        else if($selisihtanggal->d >= 1){
            $intervaldenda = $selisihtanggal->d;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $request->cek_terlambat = 1;
        }
        else if($hasilselisih->h > 3){
            $bayardenda = $harga;
            $biayadenda = $harga;
            $request->cek_terlambat = 1;
        }
        else{
            $request->cek_terlambat = 0;
            $biayadenda = 0;
            $bayardenda = 0;
        }
        //pakai promo dan driver
        if(($request->id_promo) !== NULL && ($request->id_driver) !== NULL){
            $potonganharga = promo::where('id_promo', $request->id_promo)->first();
            $usedriver = driver::where('id_driver',$request->id_driver)->first();
            $biayahariandriver = $usedriver->biaya_sewa_driver;
            $diskon = $potonganharga->jumlah_potongan;
            $totalsewapinjam = $harga * $interval;
            $totalsewadriver = $biayahariandriver * $interval;
            $grandtotal = $totalsewapinjam + $totalsewadriver;
            $jumlahdiskon = $grandtotal * ($diskon/100);
            $totalall = $grandtotal - ($grandtotal * ($diskon/100)) + $bayardenda; 
            
        }
        //tidak pakai promo dan tidak pakai driver
        else if(($request->id_promo) === NULL && ($request->id_driver) === NULL){
            $totalsewadriver = 0;
            $totalsewapinjam = $harga * $interval;
            $jumlahdiskon = 0;
            $totalall = $totalsewapinjam + $bayardenda;
        }
        //pakai promo dan tidak pakai driver
        else if(($request->id_promo) !== NULL && ($request->id_driver) === NULL){
            $totalsewadriver = 0;
            $potonganharga = promo::where('id_promo', $request->id_promo)->first();
            $diskon = $potonganharga->jumlah_potongan;
            $totalsewapinjam = $harga * $interval;
            $jumlahdiskon = $totalsewapinjam * ($diskon/100);
            $totalall = $totalsewapinjam - ($totalsewapinjam * ($diskon/100)) + $bayardenda; 
        }
        //tidak pakai promo dan pakai driver
        else if(($request->id_promo) === NULL && ($request->id_driver) !== NULL){
            $usedriver = driver::where('id_driver',$request->id_driver)->first();
            $biayahariandriver = $usedriver->biaya_sewa_driver;
            $totalsewapinjam = $harga * $interval;
            $totalsewadriver = $biayahariandriver * $interval;
            $jumlahdiskon = 0;
            $totalall = $totalsewapinjam + $totalsewadriver + $bayardenda;
        }
        
        if(($request->id_driver)===NULL)
        {
            $kode_pinjam = sprintf("02");
        }
        else{
            $kode_pinjam = sprintf("01");
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
            'waktu_kembali'=>$request->waktu_kembali,
            'tgl_selesai_pinjam'=>sprintf("null"),
            'waktu_selesai_pinjam'=>sprintf("00:00:00"),
            'jenis_peminjaman'=>$request->jenis_peminjaman,
            'jumlah_diskon'=>$jumlahdiskon,
            'total_denda'=>$bayardenda,
            'total_biaya_pinjam'=>$totalsewapinjam,
            'biaya_denda'=>$biayadenda,
            'total_sewa_driver'=>$totalsewadriver,
            'bukti_bayar'=>$buktiBayar,
            'subtotal_all'=>$totalall,
            'status_transaksi'=>sprintf("belum verifikasi"),
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
            'id_promo',
            'tgl_transaksi' => 'required|date_format:Y-m-d',
            'tgl_pinjam' => 'required|date_format:Y-m-d',
            'tgl_kembali' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'jenis_peminjaman',
            'cek_terlambat',
            'total_denda' => 'numeric',
            'total_biaya_pinjam' => 'numeric',
            'biaya_denda' => 'numeric',
            'total_sewa_driver'=> 'numeric',
            'bukti_bayar' => 'max:1024|mimes:jpg,png,jpeg|image',
            'subtotal_all' => 'numeric',
            'status_transaksi' => 'regex:/^[\pL\s\-]+$/u',
            'metode_bayar' => 'regex:/^[\pL\s\-]+$/u',
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $id_driver = $request->id_driver;
        $hitungrerata= DB::select("SELECT SUM(rating_perform_driver) / COUNT(id_driver) AS 'reratabaru' FROM transaksi WHERE id_driver = '$id_driver' ");
        $hasilrerata = array_column($hitungrerata, 'reratabaru');
        DB::update("UPDATE driver SET rerata_rating = '$hasilrerata[0]' WHERE id_driver = '$id_driver' ");

        $awal = new DateTime($request->tgl_pinjam);
        $akhir = new DateTime($request->tgl_kembali);
        $diffdays = $awal->diff($akhir);
        
        if($diffdays->m >= 1){
            $interval = ($diffdays->m) * 30 + $diffdays->d;
        }
        else if($diffdays->y >= 1){
            $interval = ($diffdays->y * 365) + ($diffdays->m * 30) + $diffdays->d ;
        }
        else{
            $interval = $diffdays->d;
        }

        $sewa = mobil::where('id_mobil', $request->id_mobil)->first();
        $harga = $sewa->biaya_sewa;

        //hitung waktu denda
        $waktukembali = new DateTime($request->waktu_kembali);
        $waktumobilmasuk = new DateTime($request->waktu_selesai_pinjam);
        $hasilselisih = $waktukembali->diff($waktumobilmasuk);
        
        //hitung tanggal denda
        $tglkembali = new DateTime($request->tgl_kembali);
        $tglselesaipinjam = new DateTime($request->tgl_selesai_pinjam);
        $selisihtanggal = $tglkembali->diff($tglselesaipinjam);
        if($selisihtanggal->m >= 1){
            $intervaldenda = ($selisihtanggal->m) * 30 + $selisihtanggal->d;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $cekterlambat = 1;
        }
        else if($selisihtanggal->y >= 1){
            $intervaldenda = ($selisihtanggal->y * 365) + ($selisihtanggal->m * 30) + $selisihtanggal->d ;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $cekterlambat = 1;
        }
        else if($selisihtanggal->d >= 1){
            $intervaldenda = $selisihtanggal->d;
            $bayardenda = $harga * $intervaldenda;
            $biayadenda = $harga;
            $cekterlambat = 1;
        }
        else if($hasilselisih->h > 3){
            $bayardenda = $harga;
            $biayadenda = $harga;
            $cekterlambat = 1;
        }
        else{
            $cekterlambat = 0;
            $bayardenda = 0;
            $biayadenda = 0;
        }
        //pakai promo dan driver
        if(($request->id_promo) !== NULL && ($request->id_driver) !== NULL){
            $potonganharga = promo::where('id_promo', $request->id_promo)->first();
            $usedriver = driver::where('id_driver',$request->id_driver)->first();
            $biayahariandriver = $usedriver->biaya_sewa_driver;
            $diskon = $potonganharga->jumlah_potongan;
            $totalsewapinjam = $harga * $interval;
            $totalsewadriver = $biayahariandriver * $interval;
            $grandtotal = $totalsewapinjam + $totalsewadriver;
            $jumlahdiskon = $grandtotal * ($diskon/100);
            $totalall = $grandtotal - ($grandtotal * ($diskon/100)) + $bayardenda; 
            
        }
        //tidak pakai promo dan tidak pakai driver
        else if(($request->id_promo) === NULL && ($request->id_driver) === NULL){
            $totalsewadriver = 0;
            $totalsewapinjam = $harga * $interval;
            $jumlahdiskon = 0;
            $totalall = $totalsewapinjam + $bayardenda;
        }
        //pakai promo dan tidak pakai driver
        else if(($request->id_promo) !== NULL && ($request->id_driver) === NULL){
            $totalsewadriver = 0;
            $potonganharga = promo::where('id_promo', $request->id_promo)->first();
            $diskon = $potonganharga->jumlah_potongan;
            $totalsewapinjam = $harga * $interval;
            $jumlahdiskon = $totalsewapinjam * ($diskon/100);
            $totalall = $totalsewapinjam - ($totalsewapinjam * ($diskon/100)) + $bayardenda; 
        }
        //tidak pakai promo dan pakai driver
        else if(($request->id_promo) === NULL && ($request->id_driver) !== NULL){
            $usedriver = driver::where('id_driver',$request->id_driver)->first();
            $biayahariandriver = $usedriver->biaya_sewa_driver;
            $totalsewapinjam = $harga * $interval;
            $totalsewadriver = $biayahariandriver * $interval;
            $jumlahdiskon = 0;
            $totalall = $totalsewapinjam + $totalsewadriver + $bayardenda;
        }

        $Transaksi->id_customer = $updateData['id_customer']; 
        $Transaksi->id_mobil = $updateData['id_mobil']; 
        $Transaksi->id_pegawai = $updateData['id_pegawai'];
        if(isset($request->id_driver)){
            $Transaksi->id_driver = $updateData['id_driver'];
        }
        else{
            $Transaksi->id_driver = NULL;
        }

        if(isset($request->id_promo)){
            $Transaksi->id_promo = $updateData['id_promo']; 
        }
        else{
            $Transaksi->id_promo = NULL; 
        }
        $Transaksi->tgl_transaksi = $updateData['tgl_transaksi']; 
        $Transaksi->tgl_pinjam = $updateData['tgl_pinjam']; 
        $Transaksi->tgl_kembali = $updateData['tgl_kembali'];
        $Transaksi->tgl_selesai_pinjam = $updateData['tgl_selesai_pinjam']; 
        $Transaksi->waktu_selesai_pinjam = $updateData['waktu_selesai_pinjam'];
        $Transaksi->jenis_peminjaman = $updateData['jenis_peminjaman'];
        $Transaksi->status_transaksi = $updateData['status_transaksi'];
        $Transaksi->total_biaya_pinjam = $totalsewapinjam;
        $Transaksi->total_sewa_driver = $totalsewadriver;
        $Transaksi->subtotal_all = $totalall;
        $Transaksi->jumlah_diskon = $jumlahdiskon;
        $Transaksi->total_denda = $bayardenda;
        $Transaksi->biaya_denda = $biayadenda;
        $Transaksi->waktu_kembali = $updateData['waktu_kembali'];
        if(isset($request->bukti_bayar)){
            $buktiBayar = $request->bukti_bayar->store('img_bukti_bayar',['disk'=>'public']);
            $Transaksi->bukti_bayar = $buktiBayar;
        }

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

    public function updateRating(Request $request, $id_transaksi){
        $Transaksi = Transaksi::find($id_transaksi); //Mencari data Transaksi berdasarkan id

        if(is_null($Transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Transaksi tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'rating_perform_driver',
            'rating_perform_ajr' => 'required|numeric'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(isset($request->rating_perform_driver)){
             $Transaksi->rating_perform_driver = $updateData['rating_perform_driver']; 
        }
        else{
            $Transaksi->rating_perform_driver = NULL; 
        }
        $Transaksi->rating_perform_ajr = $updateData['rating_perform_ajr']; 

        if($Transaksi->save()){
            return response([
                'message' => 'Update Rating Success',
                'data' => $Transaksi
            ], 200);
        } //Return data Transaksi yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Rating Failed',
            'data' => null
        ], 400);
    }

    public function tambahTransaksiPembayaran(Request $request, $id_transaksi){
        $Transaksi = Transaksi::find($id_transaksi); //Mencari data Transaksi berdasarkan id

        if(is_null($Transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Transaksi tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_promo',
            'cek_terlambat',
            'total_denda' => 'numeric',
            'total_biaya_pinjam' => 'numeric',
            'biaya_denda' => 'numeric',
            'total_sewa_driver'=> 'numeric',
            'bukti_bayar' => 'max:1024|mimes:jpg,png,jpeg|image',
            'subtotal_all' => 'numeric',
            
            'metode_bayar' => 'regex:/^[\pL\s\-]+$/u',
            'tgl_selesai_pinjam' => 'required|date_format:Y-m-d|after:tgl_pinjam',
            'waktu_selesai_pinjam' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        if(isset($request->bukti_bayar)){
            $buktiBayar = $request->bukti_bayar->store('img_bukti_bayar',['disk'=>'public']);
            $Transaksi->bukti_bayar = $buktiBayar;
        }
        $Transaksi->id_promo = $updateData['id_promo'];
       
        $Transaksi->metode_bayar = $updateData['metode_bayar'];
        $Transaksi->tgl_selesai_pinjam = $updateData['tgl_selesai_pinjam']; 
        $Transaksi->waktu_selesai_pinjam = $updateData['waktu_selesai_pinjam'];

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

    public function hitungRerataRating(){
        $test = DB::select("SELECT id_driver, SUM(rating_perform_driver) / COUNT(id_driver) AS 'reratabaru' from transaksi group by id_driver");

        if(count($test) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $test
            ], 200);
        } //Return data semua Transaksi dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Transaksi kosong
    }
}
