@extends('layouts.app')

@section('title')
Edit Jenis Pembayaran
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Edit
                </div>
                <h2 class="page-title">
                    Jenis Pembayaran
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header fw-bold">
                Mohon lengkapi seluruh kolom yang dibutuhkan.
            </div>
            <form action="{{ route('jenis-pembayaran.update', $tipe->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="jenjang_id" class="form-label fw-bold">Jenjang</label>
                                <select name="jenjang_id" id="jenjang_id" class="form-select @error('jenjang_id') is-invalid @enderror select2">
                                    <option value="">-- Pilih Jenjang --</option>
                                    @foreach ($jenjang as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $tipe->jenjang_id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                    @endforeach
                                </select>

                                @error('jenjang_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="tahun_ajaran" class="form-label fw-bold">Tahun Pelajaran</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" class="form-select @error('tahun_ajaran') is-invalid @enderror select2">
                                    <option value="">-- Pilih Tahun Pelajaran --</option>
                                    @for ($i = date('Y'); $i >= 2019; $i--)
                                        <option value="{{ $i }}" @if ($i == $tipe->tahun_ajaran) selected @endif>{{ $i }}/{{ $i + 1 }}</option>
                                    @endfor
                                </select>

                                @error('tahun_ajaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Jenis Pembayaran</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" required value="{{ $tipe->nama }}">

                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="total" class="form-label fw-bold">Total</label>
                                <input type="text" class="form-control @error('total') is-invalid @enderror number" id="total" name="total" required value="{{ $tipe->total }}">

                                @error('total')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="is_show" class="form-label fw-bold">Tampilkan jika total tagihan 0 Rupiah?</label>
                                <select name="is_show" id="is_show" class="form-select @error('is_show') is-invalid @enderror select2">
                                    <option value="1" {{ $tipe->is_show == 1 ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ $tipe->is_show == 0 ? 'selected' : '' }}>Tidak</option>
                                </select>

                                @error('is_show')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('jenis-pembayaran.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liJenisPembayaran').addClass('active');

        $('.select2').select2();

        $('input[type="text"].number').inputmask('currency', {
            'prefix': 'Rp ',
            'autoUnmask': true,
            'radixPoint': ",",
            'groupSeparator': ".",
            'digits': 0,
            'rightAlign': false,
            'removeMaskOnSubmit': true,
            'clearMaskOnLostFocus': true,
        });
    });
</script>
@endsection
