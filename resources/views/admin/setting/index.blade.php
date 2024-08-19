@extends('layouts.app')

@section('title')
Pengaturan
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Ubah
                </div>
                <h2 class="page-title">
                    Pengaturan
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
            <form action="{{ route('setting.bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @forelse ($settings as $setting)
                                <div class="mb-3">
                                    <label for="{{ $setting->key }}" class="form-label fw-bold">{{ $setting->description }}</label>

                                    @if(str_contains($setting->key, 'logo'))
                                        <input type="file" class="form-control @error($setting->key) is-invalid @enderror" id="name" name="{{ $setting->key }}">
                                        <small class="text-muted">Ukuran gambar maksimal 2MB. Format yang diperbolehkan: .jpg, .jpeg, .png. Hanya isi jika ingin mengganti gambar.</small> <br>

                                        @if(file_exists(public_path('assets/img/' . getSetting($setting->key))) && getSetting($setting->key) != '' && getSetting($setting->key) != null)
                                            <a href="{{ asset('assets/img/' . getSetting($setting->key)) }}" target="_blank" class="btn btn-primary btn-sm mt-1"><i class="fas fa-eye"></i> Lihat Gambar</a>
                                        @endif
                                    @else
                                        <input type="text" class="form-control @error($setting->key) is-invalid @enderror" id="name" name="{{ $setting->key }}" required value="{{ getSetting($setting->key) }}">
                                    @endif

                                    @error($setting->key)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            @empty

                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liUser').addClass('active');
    });
</script>
@endsection
