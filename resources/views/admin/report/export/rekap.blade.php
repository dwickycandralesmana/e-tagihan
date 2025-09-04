
<table id="table" class="table table-collapsed">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Nama Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($detail as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->pembayaran->code }}</td>
                <td>{{ $item->tanggal_pembayaran }}</td>
                <td>{{ $item->pembayaran->siswa->nama }}</td>
                <td>{{ $item->historyKelas->kelas }}</td>
                <td>{{ $item->tipe_tagihan . ' ' . $item->bulan_text }}</td>
                <td>{{ $item->metode_pembayaran }}</td>
                <td>{{ $item->bayar }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @endforelse

        @if($detail->count() > 0)
            @php
                $groupedByTagihan = $detail->groupBy('tipe_tagihan_id');
            @endphp

            @foreach($groupedByTagihan as $tagihan)
                <tr>
                    <td colspan="7" class="text-end">{{ $tagihan->first()->tipe_tagihan }}</td>
                    <td>{{ $tagihan->sum('bayar') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7" class="text-end">Total</td>
                <td>{{ $detail->sum('bayar') }}</td>
            </tr>
        @endif
    </tbody>
</table>
