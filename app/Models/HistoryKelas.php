<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryKelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'jenjang_id',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function tagihans()
    {
        return $this->hasMany(TagihanNew::class);
    }

    public function pembayaran_details()
    {
        return $this->hasMany(PembayaranDetail::class);
    }
}
