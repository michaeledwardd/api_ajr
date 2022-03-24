<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mitra extends Model
{
    use HasFactory;
    public $primarykey = 'id_mitra';
    protected $table = 'mitra';
    protected $fillable = [
        'nama_mitra',
        'alamat',
        'nomor_ktp',
        'nomor_telepon',
        'durasi_kontrak'
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
}
