<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_mobil';
    protected $table = 'mobil';
    protected $fillable = [
        'id_mitra',
        'nama_mobil',
        'jenis_transmisi',
        'bahan_bakar',
        'warna',
        'volume_bagasi',
        'fasilitas',
        'kategori_aset',
        'status_ketersediaan',
        'plat_nomor',
        'foto_mobil',
        'tipe_mobil',
        'kapasitas',
        'biaya_sewa',
        'last_service',
        'awal_kontrak',
        'akhir_kontrak',
        'nomor_stnk'
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

    public function Mitra(){
        return $this->belongsTo(Mitra::class, 'id_mitra', 'id_mitra');
    }

}
