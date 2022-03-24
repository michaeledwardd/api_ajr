<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;
    public $incrementing = false;
    public $primarykey = 'id_customer';
    protected $fillable = [
        'id_customer',
        'nama_customer',
        'tgl_lahir',
        'jenis_kelamin',
        'email_customer',
        'no_telp',
        'upload_berkas',
        'status_berkas',
        'nomor_kartupengenal',
        'no_sim',
        'asal_customer',
        'password',
        'usia_customer'
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
