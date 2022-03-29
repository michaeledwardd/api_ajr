<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'id_transaksi';
    protected $table = 'transaksi';
    protected $fillable = [
        'id_transaksi',
        'id_customer',
        'id_mobil',
        'id_pegawai',
        'id_driver',
        'id_promo',
        'tgl_transaksi',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_selesai_pinjam',
        'jenis_peminjaman',
        'cek_terlambat',
        'total_denda',
        'total_biaya_pinjam',
        'biaya_denda',
        'total_sewa_driver',
        'bukti_bayar',
        'subtotal_all',
        'status_transaksi',
        'metode_bayar',
        'rating_perform_driver',
        'rating_perform_ajr'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    public function Customer(){
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function Mobil(){
        return $this->belongsTo(Mobil::class, 'id_mobil', 'id_mobil');
    }

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function Driver(){
        return $this->belongsTo(Driver::class, 'id_driver', 'id_driver');
    }

    public function Promo(){
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }
}
