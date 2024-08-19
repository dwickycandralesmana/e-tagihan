@extends('layouts.app')

@section('title')
Edit Pengaturan
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
            <form action="{{ route('setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Deskripsi</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" readonly name="description" value="{{ $setting->description }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="value" class="form-label fw-bold">Value</label>
                                <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ $setting->value }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('setting.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liPengaturan').addClass('active');
        $('.select2').select2();
    });
</script>
@endsection
