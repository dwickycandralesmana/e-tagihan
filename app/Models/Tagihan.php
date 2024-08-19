<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    public $fillable = [
        'nis',
        'nama',
        'kelas',
        'jenjang_id',
        'column',
        'total',
    ];

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }
}
