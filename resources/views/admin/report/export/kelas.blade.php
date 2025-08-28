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
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ],
                        [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                        ]
                    ];
                @endphp

                @foreach ($listTahun as $k => $tahun)
                    <tr>
                        <td>{{ $tahun }}</td>
                    </tr>
                    <tr>
                    @foreach ($listMonth[$k] as $month)
                            <td>{{ $month }}</td>
                            @endforeach
                        </tr>
                @endforeach
            @endif
        @endforeach
    </tr>

    {{-- @foreach ($kelas as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->siswa->nama }}</td>

            @foreach ($tagihan as $tagihanData)
                @php
                    $tagihanNew = \App\Models\TagihanNew::where('tipe_tagihan_id', $tagihanData->id)
                                           ->where('siswa_id', $item->siswa_id)
                                           ->where('tahun_ajaran', $item->tahun_ajaran)
                                           ->first();

                    $sudahBayar = $item->pembayaran_details?->sum('bayar');
                @endphp

                @if($tagihanData->key == 'daftar_ulang')
                    <td>{{ $tagihanNew->total }}</td>
                    <td>{{ $sudahBayar }}</td>
                    <td>{{ $tagihanNew->total - $sudahBayar }}</td>
                @elseif($tagihanData->key == 'spp')
                    <td>{{ $tagihanData->nama }}</td>
                @endif
            @endforeach
        </tr>
    @endforeach --}}
</table>
