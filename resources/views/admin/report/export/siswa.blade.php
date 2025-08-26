<table border="1">
    <tr>
        <td>NIS</td>
        <td>:</td>
        <td>{{ $kelas->siswa->nis }}</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>:</td>
        <td>{{ $kelas->siswa->nama }}</td>
    </tr>
    <tr>
        <td>Kelas</td>
        <td>:</td>
        <td>{{ $kelas->kelas }}</td>
    </tr>
    <tr>
        <td>Tahun Pelajaran</td>
        <td>:</td>
        <td>{{ $kelas->tahun_ajaran }}</td>
    </tr>
    <tr>
        <td>No</td>
        <td>Nama Pembayaran</td>
        <td>Total Biaya</td>
        <td>Total Potongan</td>
        <td>Sudah Dibayar</td>
        <td>Tunggakan</td>
    </tr>
    @php
        $totalBiaya        = 0;
        $totalPotongan     = 0;
        $totalSudahDibayar = 0;
        $totalTunggakan    = 0;
        $index             = 1;
    @endphp
    @foreach ($kelas->tagihans as $item)
        @if(!in_array($item->tipe_tagihan->key, ['spp', 'angsuran_ujian']))
            @php
                $tempTotalBiaya        = $item->total;
                $tempTotalPotongan     = $item->pembayaran_details->sum('potongan');
                $tempTotalSudahDibayar = $item->pembayaran_details->sum('bayar');
                $tempTotalTunggakan    = $tempTotalBiaya - ($tempTotalPotongan + $tempTotalSudahDibayar);

                $totalBiaya        += $tempTotalBiaya;
                $totalPotongan     += $tempTotalPotongan;
                $totalSudahDibayar += $tempTotalSudahDibayar;
                $totalTunggakan    += $tempTotalTunggakan;
            @endphp

            <tr>
                <td>{{ $index++ }}</td>
                <td>{{ $item->tipe_tagihan->nama }}</td>
                <td>{{ $tempTotalBiaya }}</td>
                <td>{{ $tempTotalPotongan }}</td>
                <td>{{ $tempTotalSudahDibayar }}</td>
                <td>{{ $tempTotalTunggakan }}</td>
            </tr>
        @else
            @php
                $thisYear = $kelas->tahun_ajaran;
                $nextYear = $kelas->tahun_ajaran + 1;
                \Carbon\Carbon::setLocale('id');

                $tagihanPerBulan = $item->total / 12;

                if($item->tipe_tagihan->key == 'spp' && $item->tipe_tagihan->jenjang_id == 1){
                    $tagihanPerBulan = $item->total / 11;
                }
            @endphp
            @for($i = 7; $i <= 12; $i++)
                @php
                    $details = $item->pembayaran_details->where('bulan', $i)->where('key', $item->tipe_tagihan->key);

                    $tempTotalPotongan     = $details->sum('potongan');
                    $tempTotalSudahDibayar = $details->sum('bayar');
                    $tempTotalTunggakan    = $tagihanPerBulan - ($tempTotalPotongan + $tempTotalSudahDibayar);

                    $totalBiaya        += $tagihanPerBulan;
                    $totalPotongan     += $tempTotalPotongan;
                    $totalSudahDibayar += $tempTotalSudahDibayar;
                    $totalTunggakan    += $tempTotalTunggakan;
                @endphp

                <tr>
                    <td>{{ $index++ }}</td>
                    <td> {{ $item->tipe_tagihan->nama . ' ' . \Carbon\Carbon::parse("$thisYear-$i-01")->translatedFormat('F') }} {{ $thisYear }}</td>
                    <td>
                        @php
                            $tempTotal = $details->sum('bayar');
                            $tempPotongan = $details->sum('potongan');

                            if($item->tipe_tagihan->key == 'spp' && $item->tipe_tagihan->jenjang_id == 1 && $i == 7){
                                $tempTagihan     = 0;
                            }else{
                                $tempTagihan = $tagihanPerBulan;
                            }
                        @endphp

                        {{ $tempTagihan }}
                    </td>
                    <td>
                        {{ $tempTotal }}
                    </td>
                    <td>
                        {{ $tempPotongan }}
                    </td>
                    <td>
                        @if($tempTagihan == 0)
                            {{ $tempTagihan }}
                        @else
                            {{ $tagihanPerBulan - ($tempTotal + $tempPotongan) }}
                        @endif
                    </td>
                </tr>
            @endfor

            @for($i = 1; $i <= 6; $i++)
                @php
                    $details = $item->pembayaran_details->where('bulan', $i)->where('key', $item->tipe_tagihan->key);

                    $tempTotalPotongan     = $details->sum('potongan');
                    $tempTotalSudahDibayar = $details->sum('bayar');
                    $tempTotalTunggakan    = $tagihanPerBulan - ($tempTotalPotongan + $tempTotalSudahDibayar);

                    $totalBiaya        += $tagihanPerBulan;
                    $totalPotongan     += $tempTotalPotongan;
                    $totalSudahDibayar += $tempTotalSudahDibayar;
                    $totalTunggakan    += $tempTotalTunggakan;
                @endphp

                <tr>
                    <td>{{ $index++ }}</td>
                    <td> {{ $item->tipe_tagihan->nama . ' ' . \Carbon\Carbon::parse("$nextYear-$i-01")->translatedFormat('F') }} {{ $nextYear }}</td>
                    <td>{{ $tagihanPerBulan }}</td>
                    <td>
                        @php
                            $tempTotal = $details->sum('bayar');
                            $tempPotongan = $details->sum('potongan');
                        @endphp

                        {{ $tempTotal }}
                    </td>
                    <td>
                        {{ $tempPotongan }}
                    </td>
                    <td>
                        {{ $tagihanPerBulan - ($tempTotal + $tempPotongan) }}
                    </td>
                </tr>
            @endfor
        @endif
    @endforeach
    <tr>
        <td colspan="2">Total</td>
        <td>{{ $totalBiaya }}</td>
        <td>{{ $totalPotongan }}</td>
        <td>{{ $totalSudahDibayar }}</td>
        <td>{{ $totalTunggakan }}</td>
    </tr>
</table>
