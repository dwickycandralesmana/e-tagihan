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

            @endif
        @endforeach
    </tr>

    @foreach ($kelas as $item)
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
    @endforeach
</table>
