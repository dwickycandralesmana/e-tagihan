<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Tahun Ajaran</th>
            <th>Kelas</th>
            <th>Siswa</th>
            <th>Jenis Pembayaran</th>
            <th>Potongan</th>
            <th>Dibuat Oleh</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->tanggal_pembayaran }}</td>
                <td>{{ $item->tahun_ajaran }}</td>
                <td>{{ $item->nama_kelas }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->tipe_tagihan }} {{ $item->bulan_text }}</td>
                <td>{{ $item->potongan }}</td>
                <td>{{ $item->created_by }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
