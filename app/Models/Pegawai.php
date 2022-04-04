<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pegawai extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pegawai';
    protected $table = 'pegawai';
    protected $fillable = [
        'id_role',
        'nama_pegawai',
        'foto_pegawai',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'email',
        'password',
        'is_aktif'
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

    public function Role(){
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}
