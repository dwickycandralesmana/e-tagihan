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
        <td>Jumlah Pembayaran</td>
        <td>Tanggal Pembayaran</td>
        <td>Metode Pembayaran</td>
        <td>Yang Menerima</td>
    </tr>
    @foreach ($kelas->pembayaran_details as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->tagihanNew->tipe_tagihan->nama }} {{ $item->bulan_text }}</td>
            <td>{{ $item->bayar }}</td>
            <td>{{ $item->pembayaran->tanggal_pembayaran }}</td>
            <td>{{ $item->pembayaran->metode_pembayaran }}</td>
            <td>{{ $item->pembayaran->createdBy->name }}</td>
        </tr>
    @endforeach
