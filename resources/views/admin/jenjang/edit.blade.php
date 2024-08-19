@extends('layouts.app')

@section('title')
Edit Jenjang
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
                    Jenjang
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
            <form action="{{ route('jenjang.update', $jenjang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Jenjang</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" required value="{{ $jenjang->nama }}">

                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="catatan" class="form-label fw-bold">Deskripsi</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" required>{{ $jenjang->catatan }}</textarea>

                                @error('catatan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary mb-3" id="btnAddRow"><i class="fas fa-plus"></i> Tambah Kolom</button>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Key Column Excel</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (json_decode($jenjang->column, true) ?? [] as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="text" class="form-control" name="key[]" value="{{ $value['key'] }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="label[]" value="{{ $value['label'] }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-delete-row"><i class="fas fa-trash me-0"></i></button>
                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>

                            <b>
                                NB: Key Column Excel adalah nama kolom pada file excel yang akan diimport (tanpa spasi dan huruf kecil semua). Deskripsi adalah nama kolom yang akan ditampilkan pada aplikasi.
                            </b>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('jenjang.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liJenjang').addClass('active');
        //summernote
        $('textarea').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $(document).on('click', '.btn-delete-row', function(){
            $(this).closest('tr').remove();

            $('table tbody tr').each(function(index){
                $(this).find('td:first').text(index + 1);
            });
        });

        $('#btnAddRow').click(function(){
            let row = `
                <tr>
                    <td></td>
                    <td>
                        <input type="text" class="form-control" name="key[]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="label[]" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-delete-row"><i class="fas fa-trash me-0"></i></button>
                    </td>
                </tr>
            `;

            $('table tbody').append(row);

            $('table tbody tr').each(function(index){
                $(this).find('td:first').text(index + 1);
            });
        });
    });
</script>
@endsection
