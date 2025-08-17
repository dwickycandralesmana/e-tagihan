<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanNew extends Model
{
    use HasFactory;

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
