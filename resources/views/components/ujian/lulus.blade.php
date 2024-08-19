<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header fw-bold">
                HASIL UJIAN
            </div>
            <div class="card-body text-justify">
                <p>
                    Selamat, Anda berhasil lulus ujian {{ $ujian->ujian->nama }}. Berikut rincian nilai Anda:
                    <h3 class="text-center">RINCIAN NILAI</h3>
                    <table class="table table-bordered" border="0">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jumlah Soal</th>
                                <th>Benar</th>
                                <th>Salah</th>
                                <th>Kosong</th>
                                <th>Nilai Minimal</th>
                                <th>Nilai Anda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (json_decode($ujian->list_nilai) as $k => $nilai)
                                @php
                                    $soal            = $ujian->soal->where('tipe', $k)->pluck('id');
                                    $total           = $soal->count();
                                    $manajerial      = 0;
                                    $sosial          = 0;
                                    $manajerialBenar = 0;
                                    $manajerialSalah = 0;
                                    $sosialBenar     = 0;
                                    $sosialSalah     = 0;
                                    $manajerialKosong = 0;
                                    $sosialKosong     = 0;

                                    if($k == "Tes Kompetensi Manajerial & Sosial Kultural"){
                                        $soal         = $ujian->soal->whereIn('tipe', ["Tes Kompetensi Manajerial", "Tes Kompetensi Sosial Kultural"])->pluck('id');
                                        $total        = $soal->count();

                                        $manajerial      = $ujian->soal->where('tipe', "Tes Kompetensi Manajerial")->pluck('id');
                                        $sosial          = $ujian->soal->where('tipe', "Tes Kompetensi Sosial Kultural")->pluck('id');
                                        $manajerialTotal = $manajerial->count();
                                        $sosialTotal     = $sosial->count();
                                        $manajerialBenar = app\Models\HasilJawaban::whereIn('hasil_soal_id', $soal)->where('is_dipilih', 1)->where('is_benar', 1)->whereHas('soal', function($q) use ($manajerial){
                                            $q->where('tipe', "Tes Kompetensi Manajerial");
                                        })->count();

                                        $manajerialSalah = app\Models\HasilJawaban::whereIn('hasil_soal_id', $soal)->where('is_dipilih', 1)->where('is_benar', 0)->whereHas('soal', function($q) use ($manajerial){
                                            $q->where('tipe', "Tes Kompetensi Manajerial");
                                        })->count();

                                        $sosialBenar = app\Models\HasilJawaban::whereIn('hasil_soal_id', $soal)->where('is_dipilih', 1)->where('is_benar', 1)->whereHas('soal', function($q) use ($sosial){
                                            $q->where('tipe', "Tes Kompetensi Sosial Kultural");
                                        })->count();

                                        $sosialSalah = app\Models\HasilJawaban::whereIn('hasil_soal_id', $soal)->where('is_dipilih', 1)->where('is_benar', 0)->whereHas('soal', function($q) use ($sosial){
                                            $q->where('tipe', "Tes Kompetensi Sosial Kultural");
                                        })->count();

                                        $manajerialKosong = $manajerialTotal - $manajerialBenar - $manajerialSalah;
                                        $sosialKosong     = $sosialTotal - $sosialBenar - $sosialSalah;

                                    }

                                    $checkJawaban = app\Models\HasilJawaban::whereIn('hasil_soal_id', $soal)->get();

                                    $totalBenar = $checkJawaban->where('is_dipilih', 1)->where('is_benar', 1)->count();
                                    $totalSalah = $checkJawaban->where('is_dipilih', 1)->where('is_benar', 0)->count();

                                    $totalKosong = $total - $totalBenar - $totalSalah;
                                    $nilaiMinimal = json_decode($ujian->ujian->list_nilai)->$k;
                                @endphp

                                @if($k == "Tes Kompetensi Manajerial & Sosial Kultural")
                                    <tr>
                                        <td>Tes Kompetensi Manajerial</td>
                                        <td>{{ $manajerialTotal }}</td>
                                        <td>{{ $manajerialBenar }}</td>
                                        <td>{{ $manajerialSalah }}</td>
                                        <td>{{ $manajerialKosong }}</td>
                                        <td>{{ $nilaiMinimal }}</td>
                                        <td>{{ $nilai }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tes Kompetensi Sosial Kultural</td>
                                        <td>{{ $sosialTotal }}</td>
                                        <td>{{ $sosialBenar }}</td>
                                        <td>{{ $sosialSalah }}</td>
                                        <td>{{ $sosialKosong }}</td>
                                        <td rowspan="2" valign="middle">{{ $nilaiMinimal }}</td>
                                        <td rowspan="2" valign="middle">{{ $nilai }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $k }}</td>
                                        <td>{{ $total }}</td>
                                        <td>{{ $totalBenar }}</td>
                                        <td>{{ $totalSalah }}</td>
                                        <td>{{ $totalKosong }}</td>
                                    </tr>
                                @endif
                            @empty

                            @endforelse
                        </tbody>
                    </table>

                    <div class="mb-3">
                        <a href="{{ route('simulasi.ujian.cetak', $ujian->id) }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Download Hasil Ujian</a></a>
                    </div>

                    @if(!$ujian->ujian->pembahasan)
                        <div class="mb-3" id="request-pembahasan">
                            <b>Silahkan hubungi admin untuk mendapatkan akses pembahasan soal.</b><Br>
                            <button type="button" class="btn btn-success mt-2 swal-request"> <i class="fas fa-envelope"></i> Hubungi Admin</button>
                            Atau lakukan pembayaran untuk mendapatkan akses pembahasan soal.
                        </div>
                    @endif

                    <a href="{{ route('simulasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $(document).on('click', '.swal-request', function () {
            $.ajax({
                url: "{{ route('simulasi.ujian.pembahasan', $ujian->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ujian_id: "{{ $ujian->ujian_id }}"
                },
                success: function (response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        });

                        $('#request-pembahasan').html(response.html);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                        });
                    }
                }
            });
        });
    });
</script>
