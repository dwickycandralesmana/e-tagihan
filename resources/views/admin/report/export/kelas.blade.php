<table border="1">
    <tr>
        <td rowspan="2">NO</td>
        <td rowspan="2">NIS</td>
        <td rowspan="2">NAMA SISWA</td>

        @foreach ($tagihan as $item)
            @if($item->key == 'daftar_ulang')
                <td rowspan="2">DU TOTAL</td>
                <td rowspan="2">DU DIBAYAR</td>
                <td rowspan="2">KEKURANGAN DU</td>
            @elseif($item->key == 'spp')
                @php
                    $listTahun = [
                        $item->tahun_ajaran,
                        $item->tahun_ajaran + 1,
                    ];

                    $listMonth = [
                        [
                            7 => 'JUL',
                            8 => 'AGS',
                            9 => 'SEP',
                            10 => 'OKT',
                            11 => 'NOV',
                            12 => 'DES',
                        ],
                        [
                            1 => 'JAN',
                            2 => 'FEB',
                            3 => 'MAR',
                            4 => 'APR',
                            5 => 'MEI',
                            6 => 'JUN',
                        ]
                    ];
                @endphp

                @foreach ($listTahun as $k => $tahun)
                    <td colspan="6">{{ $tahun }}</td>
                @endforeach
                <td rowspan="2">SPP KURANG</td>
            @else
                <td rowspan="2">{{ $item->nama }}</td>
            @endif
        @endforeach

        <td rowspan="2">TOTAL TAGIHAN</td>
    </tr>
    <tr>
        @foreach ($tagihan as $item)
            @if($item->key == 'spp')
                @foreach ($listMonth as $monthGroup)
                    @foreach ($monthGroup as $month)
                        <td>{{ $month }}</td>
                    @endforeach
                @endforeach
            @endif
        @endforeach
    </tr>

    @foreach ($kelas as $item)
        @php
            $totalTagihan      = 0;
            $totalPotongan     = 0;
            $totalSudahDibayar = 0;
            $totalTunggakan    = 0;
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->siswa->nis }}</td>
            <td>{{ $item->siswa->nama }}</td>

            @foreach ($tagihan as $tagihanData)
                @php
                    $tagihanNew = \App\Models\TagihanNew::where('tipe_tagihan_id', $tagihanData->id)
                    ->where('siswa_id', $item->siswa_id)
                    ->where('tahun_ajaran', $item->tahun_ajaran)
                    ->first();

                    $sudahBayar    = $item->pembayaran_details?->sum('bayar');
                    $totalTagihan += $tagihanNew->kurang();
                @endphp

                @if($tagihanData->key == 'daftar_ulang')
                    <td>{{ $tagihanNew->total }}</td>
                    <td>{{ $sudahBayar }}</td>
                    <td>{{ $tagihanNew->total - $sudahBayar }}</td>
                @elseif($tagihanData->key == 'spp')
                    @foreach ($listMonth as $monthGroup)
                        @foreach ($monthGroup as $bulan => $month)
                            <td>
                                @php
                                    $tagihanPerBulan = $tagihanNew->sppPerBulan();
                                    $sudahBayar      = $tagihanNew->pembayaran_details?->where('bulan', $bulan)->sum('bayar');

                                    $sisaBayar = $tagihanPerBulan - $sudahBayar;
                                @endphp
                                {{ $sisaBayar }}
                            </td>
                        @endforeach
                    @endforeach
                    <td>
                        {{ $tagihanNew->kurang() }}
                    </td>
                @else
                    <td>
                        {{ $tagihanNew->kurang() }}
                    </td>
                @endif
            @endforeach

            <td>{{ $totalTagihan }}</td>
        </tr>
    @endforeach
</table>
