<div class="row">
    <div class="col-md-7 col-sm-12">
        <div class="card">
            <div class="card-header">
                PERATURAN UJIAN
            </div>
            <div class="card-body text-justify">
                <p>
                    Sebelum mengikuti ujian, pastikan anda <b>telah membaca</b> peraturan ujian dibawah ini:
                </p>
                <ol>
                    <li>Peserta diharapkan mempunyai koneksi internet yang baik.</li>
                    <li>Peserta dilarang membawa alat komunikasi selama ujian berlangsung.</li>
                    <li>Peserta dilarang membawa buku atau dokumen selama ujian berlangsung.</li>
                    <li>Peserta dilarang menggunakan ponsel selama ujian berlangsung.</li>
                    <li>Peserta dilarang menggunakan / membuka aplikasi lain selama ujian berlangsung.</li>
                    <li>Peserta dilarang menggunakan / membuka browser dan atau tab baru selama ujian berlangsung.</li>
                    <li>Peserta dilarang menekan tombol (windows untuk pengguna Windows) / (command untuk pengguna MacOS) / berganti tab selama ujian berlangsung. Jika melanggar pelaturan ini, maka peserta akan secara otomatis dianggap <b>GAGAL</b> dalam ujian.</li>
                    <li>Ujian akan dilaksanakan selama {{ $ujian->kompetensi->durasi }} menit dan terbatas untuk {{ $ujian->kompetensi->kuota }} orang pertama. Ujian akan otomatis tersimpan jika waktu / peserta yang lulus sudah melewati batas yang ditentukan.</li>
                </ol>

                <p>
                    Jika sistem mendeteksi adanya pelanggaran, maka secara otomatis peserta akan dinyatakan <b>GAGAL</b>  dalam ujian.
                </p>

                @if($ujian->status == "MENUNGGU_PERSETUJUAN")
                    <p>
                        Mohon menunggu persetujuan dari admin untuk mengikuti ujian. Jika dalam 1 menit anda tidak mendapatkan persetujuan, silahkan hubungi admin dengan memberikan kode ujian berikut ini:
                        <br><br>
                        <b>{{ $ujian->nomor_ujian }}</b>
                    </p>
                @else
                    <p>
                        Ujian telah disetujui. Silahkan klik tombol <b>MULAI UJIAN</b> untuk memulai ujian.
                    </p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-5 col-sm-12">
        <div class="card">
            <div class="card-header">
                Data Peserta dan Ujian
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" value="{{ $ujian->peserta->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="nomor_ujian">Nomor Ujian</label>
                            <input type="text" class="form-control" id="nomor_ujian" value="{{ $ujian->nomor_ujian }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="nama_ujian">Nama Ujian</label>
                            <input type="text" class="form-control" id="nama_ujian" value="{{ $ujian->kompetensi->nama }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="durasi">Durasi</label>
                            <input type="text" class="form-control" id="durasi" value="{{ $ujian->kompetensi->durasi }} Menit" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12 text-left">
                        <input type="checkbox" checked id="disclaimer">
                        <label for="disclaimer" class="cursor-pointer">Saya telah membaca dan setuju dengan peraturan ujian.</label>
                    </div>
                    <button class="btn btn-primary w-100 btn-mulai-ujian">MULAI UJIAN</button>
                </div>
            </div>
        </div>
    </div>
</div>
