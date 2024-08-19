<div class="container-fluid">
    <form action="{{ route('simulasi.ujian.simpan', $ujian->id) }}" method="POST" id="form-ujian">
        @csrf
        <div class="row">
            <div class="col-md-7 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span id="nomorSoal" class="fw-bold">Soal 1 / {{ $ujian->soal->count() }}</span>
                        <span class="float-end">
                            <button type="button" class="btn btn-default" id="prevBtn" onclick="nextPrev(-1)" style="display: none;"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev(1)"><i class="fas fa-chevron-right"></i></button>
                        </span>
                    </div>
                    <div class="card-body">
                        @forelse ($ujian->soal as $item)
                            <div class="tab">
                                @php $indexSoal = $loop->iteration; $alias = range('A', 'E'); @endphp
                                <b>SOAL {{ $item->tipe }}</b>
                                <p>
                                    {!! $item->soal !!}
                                </p>

                                <p>
                                    <table>
                                    @forelse ($item->jawaban as $key => $listJawaban)
                                        <tr>
                                            <td class="py-2 d-flex align-items-start">
                                                <input type="radio" name="jawaban[{{ $item->id }}]" value="{{ $listJawaban->id }}"
                                                data-soal-id="{{ $listJawaban->hasil_soal_id }}"
                                                data-soal="{{ $indexSoal }}"
                                                @checked($listJawaban->is_dipilih == 1)
                                                id="jawaban{{ $listJawaban->id }}" class="pilihan-jawaban float-left" required>
                                            </td>
                                            <td class="pb-2 pt-1">
                                                <label for="jawaban{{ $listJawaban->id }}" class="cursor-pointer font-weight-light m-0">
                                                    {{ $alias[$key] }}. {!! $listJawaban->jawaban !!}
                                                </label>
                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" class="btn btn-warning mt-2 btn-ragu" data-soal-id="{{ $item->id }}" data-soal='{{ $loop->iteration }}'>Ragu-ragu</button>
                                        </td>
                                    </tr>
                                    </table>
                                </p>
                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 fw-bold">
                                <table>
                                    <tr>
                                        <td>Durasi</td>
                                        <td>:</td>
                                        <td>{{ $ujian->ujian->durasi }} Menit</td>
                                    </tr>
                                    <tr>
                                        <td>Waktu Selesai</td>
                                        <td>:</td>
                                        <td>{{ \Carbon\Carbon::parse($ujian->batas_waktu)->format('j F Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Soal</td>
                                        <td>:</td>
                                        <td id="jumlahSoal">{{ $ujian->soal->count() }}</td>
                                    </tr>
                                    <tr class="text-success">
                                        <td>Dijawab</td>
                                        <td>:</td>
                                        <td id="soal-dijawab">{{ $ujian->soal->where('status', 'DIJAWAB')->count() }}</td>
                                    </tr>
                                    <tr class="text-warning">
                                        <td>Belum Dijawab</td>
                                        <td>:</td>
                                        <td id="soal-belum-dijawab">{{ $ujian->soal->whereIn('status', ['BELUM_DIJAWAB', 'RAGU'])->count() }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 text-center fw-bold">
                                <div class="mb-3">
                                    SISA WAKTU
                                </div>
                                <h2 id="counter">
                                    00:00:00
                                </h2>
                                <div class="row">

                                </div>
                            </div>
                            <div class="col-12 text-center fw-bold mb-3 fs-3 border-top py-2">
                                DAFTAR SOAL
                            </div>
                            <div style="max-height: 400px; overflow-y: auto" class="col-12">
                                <div class="row">
                                    @forelse ($ujian->soal as $item)
                                        @php
                                            $class = "btn-default";

                                            if($item->status == "DIJAWAB"){
                                                $class = "btn-success";
                                            }elseif($item->status == "RAGU"){
                                                $class = "btn-warning";
                                            }
                                        @endphp
                                        <div class="col-3 my-2">
                                            <button type="button" class="btn {{ $class }} w-100" id="btnSoal{{ $loop->iteration }}" onclick="return nextPrev({{ $loop->index }}, true)">{{ $loop->iteration }}</button>
                                        </div>
                                    @empty

                                    @endforelse
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                                <button class="btn btn-primary w-100" id="btnSelesai" type="button">Selesai</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    const zeroPad = (num, places) => String(num).padStart(places, '0')

    $(function() {
        showTab(0);
        var countDownDate = "{{ \Carbon\Carbon::parse($ujian->batas_waktu)->getPreciseTimestamp(3); }}";

        var x = setInterval(function() {
            var now      = new Date().getTime();
            var distance = countDownDate - now;
            var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds  = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("counter").innerHTML = zeroPad(hours, 2) + ":" + zeroPad(minutes, 2) + ":" + zeroPad(seconds, 2);

            if (distance < 0) {
                clearInterval(x);
                $('#form-ujian').submit();
            }
        }, 1000);

        var countdownUjian = "{{ \Carbon\Carbon::parse($ujian->ujian->end_date)->getPreciseTimestamp(3); }}";

        var x = setInterval(function() {
            var now      = new Date().getTime();
            var distance = countdownUjian - now;
            var days     = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours    = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes  = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds  = Math.floor((distance % (1000 * 60)) / 1000);

            if (distance < 0) {
                clearInterval(x);
                $('#form-ujian').submit();
            }
        }, 1000);

        // $(window).on('keydown', function(e){
        //     if(e.which == 123){
        //         return false;
        //     }
        // });

        var ujianGagal = function(){
            $.ajax({
                url: "{{ route('simulasi.ujian.gagal', $ujian->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    window.location.reload();
                }
            });
        }

        $(window).on('blur', function(){
            // ujianGagal();
        });
    })
</script>
