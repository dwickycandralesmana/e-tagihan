<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanNew extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenjang_id',
        'siswa_id',
        'tipe_tagihan_id',
        'total',
        'potongan',
        'deskripsi',
    ];

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tipe_tagihan()
    {
        return $this->belongsTo(TipeTagihan::class);
    }

    public function pembayaran_details()
    {
        return $this->hasMany(PembayaranDetail::class);
    }
}
