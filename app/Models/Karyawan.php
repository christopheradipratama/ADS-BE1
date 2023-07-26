<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cuti;
use Carbon\Carbon;

class Karyawan extends Model
{
    use HasFactory, HasApiTokens;
    
    protected $primaryKey = 'nomor_induk';
    public $incrementing = false;

    protected $fillable = [
        'nomor_induk',
        'nama', 
        'alamat', 
        'tanggal_lahir',
        'tanggal_bergabung'
    ];

    public function getCreatedAtAttribute() {
        if(!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdateAtAtrribute() {
        if(!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    protected function serializeDate(\DateTimeInterface $date){
        return $date->format('Y-m-d H:i:s');
    }

    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class, 'nomor_induk', 'nomor_induk');
    }
}