<div class="row">
    <div class="row">
        <div class="col-12">
            <p class="fw-bold fs-3">
                Kepada: <br>
                1. Ketua Umum DPC PERADI cq. Bidang Keanggotaan <br>

                <label for="ketua_dpc_asal" class="label-required fw-bold fs-3">2. Ketua DPC Asal:</label>
            </p>
            <div class="form-group mb-3">
                <textarea name="ketua_dpc_asal" id="ketua_dpc_asal" class="form-control @error('ketua_dpc_asal') is-invalid @enderror" rows="3">{{ old('ketua_dpc_asal') }}</textarea>
                <small class="text-muted">(DPC Domisili Asal)</small>

                @error('ketua_dpc_asal')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="ketua_dpc_tujuan" class="label-required fw-bold fs-3">Ketua DPC Tujuan:</label>
                <textarea name="ketua_dpc_tujuan" id="ketua_dpc_tujuan" class="form-control @error('ketua_dpc_tujuan') is-invalid @enderror" rows="3">{{ old('ketua_dpc_tujuan') }}</textarea>
                <small class="text-muted">(DPC Domisili tujuan pindah)</small>

                @error('ketua_dpc_tujuan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12">
        <p class="fw-bold fs-3 text-center">
            SAYA YANG BERTANDA TANGAN DI BAWAH INI:
        </p>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="nia" class="label-required fw-bold fs-3">NIA (Nomor Induk Advokat)</label>
            <input type="number" class="form-control @error('nia') is-invalid @enderror" id="nia" name="nia" value="{{ old('nia') }}">

            @error('nia')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="name" class="label-required fw-bold fs-3">Nama Lengkap & Gelar</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="phone" class="label-required fw-bold fs-3">Nomor Handphone</label>
            <input type="tel" name="phone" id="telPhone" class="form-control w-100 @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
            <input type="hidden" class="findPhone" name="phone" id="phone" value="{{ old('phone') }}">

            <div class="phone-info alert-info mt-2 text-primary" style="display: none;font-size:10px"></div>
            <div class="phone-error alert-error mt-2 text-danger" style="display: none;font-size:10px"></div>

            @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="email" class="label-required fw-bold fs-3">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="dpc_asal" class="label-required fw-bold fs-3">DPC Asal</label>
            <input type="text" class="form-control @error('dpc_asal') is-invalid @enderror" id="dpc_asal" name="dpc_asal" value="{{ old('dpc_asal') }}">

            @error('dpc_asal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="tgl_dpc_tujuan" class="label-required fw-bold fs-3">Sejak Tanggal</label>
            <input type="date" class="form-control @error('tgl_dpc_tujuan') is-invalid @enderror" id="tgl_dpc_tujuan" name="tgl_dpc_tujuan" value="{{ old('tgl_dpc_tujuan') }}">

            @error('tgl_dpc_tujuan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group mb-3">
            <label for="alamat_asal" class="label-required fw-bold fs-3">Domisili saya telah pindah dari semula beralamat rumah/kantor</label>
            <textarea name="alamat_asal" id="alamat_asal" class="form-control @error('alamat_asal') is-invalid @enderror" rows="3">{{ old('alamat_asal') }}</textarea>

            @error('alamat_asal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group mb-3">
            <label for="alamat_tujuan" class="label-required fw-bold fs-3">Pindah ke alamat rumah/kantor</label>
            <textarea name="alamat_tujuan" id="alamat_tujuan" class="form-control @error('alamat_tujuan') is-invalid @enderror" rows="3">{{ old('alamat_tujuan') }}</textarea>

            @error('alamat_tujuan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group mb-3">
            <label for="dpc_tujuan" class="label-required fw-bold fs-3">Untuk itu saya mengajukan permohonan pindah ke DPC </label>
            <textarea name="dpc_tujuan" id="dpc_tujuan" class="form-control @error('dpc_tujuan') is-invalid @enderror" rows="3">{{ old('dpc_tujuan') }}</textarea>

            @error('dpc_tujuan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <label for="photo_keterangan" class="label-required fw-bold fs-3">Lampiran KTP atau Keterangan dari kantor </label>
            <input type="file" class="form-control @error('photo_keterangan') is-invalid @enderror" id="photo_keterangan" name="photo_keterangan" value="{{ old('photo_keterangan') }}">

            @error('photo_keterangan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
