<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $primarykey = 'id_driver';
    protected $table = 'driver';
    protected $fillable = [
        'id_driver',
        'nama_driver', 
        'jenis_kelamin',
        'alamat',
        'email_driver',
        'password',
        'foto_driver',
        'status_tersedia',
        'biaya_sewa_driver',
        'no_telp',
        'tgl_lahir',
        'rerata_rating',
        'mahir_inggris',
        'upload_sim',
        'upload_bebas_napza',
        'upload_sehat_jiwa',
        'upload_sehat_jasmani',
        'upload_skck'
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
