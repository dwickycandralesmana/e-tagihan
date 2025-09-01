<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranDetail extends Model
{
    use HasFactory;

    protected $appends = ['bulan_text'];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function tagihanNew()
    {
        return $this->belongsTo(TagihanNew::class);
    }

    public function getBulanTextAttribute()
    {
        if (empty($this->bulan)) {
            return '';
        }

        $year = $this->bulan >= 1 && $this->bulan <= 6 ? $this->tahun_ajaran + 1 : $this->tahun_ajaran;
        $bulan = $this->bulan ? " - " . Carbon::parse($year . '-' . $this->bulan . '-01')->format('M') : "";

        return $bulan . " " . $year;
    }

    public function historyKelas()
    {
        return $this->belongsTo(HistoryKelas::class);
    }
}
