<div class="row">
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
            <label for="name_ktpa" class="label-required fw-bold fs-3">Nama & Gelar di KTPA</label>
            <input type="text" class="form-control @error('name_ktpa') is-invalid @enderror" id="name_ktpa" name="name_ktpa" value="{{ old('name_ktpa') }}">
            <small class="text-muted">(max 40 karakter termasuk spasi, tanda baca dan gelar)</small>

            @error('name_ktpa')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="gender_id" class="label-required fw-bold fs-3">Jenis Kelamin</label>
            <select name="gender_id" id="gender_id" class="form-control select2">
                @foreach ($genders as $gender)
                    <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                @endforeach
            </select>

            @error('gender_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="birth_place" class="label-required fw-bold fs-3">Tempat Lahir</label>
            <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">

            @error('birth_place')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="birth_date" class="label-required fw-bold fs-3">Tanggal Lahir</label>
            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">

            @error('birth_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="religion_id" class="label-required fw-bold fs-3">Agama</label>
            <select name="religion_id" id="religion_id" class="form-control select2">
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                @endforeach
            </select>

            @error('religion_id')
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
    <div class="col-sm-12">
        <div class="form-group mb-3">
            <label for="address" class="label-required fw-bold fs-3">Alamat</label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>

            @error('address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="firm_name" class="label-required fw-bold fs-3">Nama Kantor</label>
            <input type="text" class="form-control @error('firm_name') is-invalid @enderror" id="firm_name" name="firm_name" value="{{ old('firm_name') }}">

            @error('firm_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="firm_phone" class="label-required fw-bold fs-3">Nomor Kantor</label>
            <input type="tel" name="firm_phone" id="telFirmPhone" class="form-control w-100 @error('firm_phone') is-invalid @enderror" value="{{ old('firm_phone') }}">
            <input type="hidden" class="findFirmPhone" name="firm_phone" id="firm_phone" value="{{ old('firm_phone') }}">

            <div class="firm-phone-info alert-info mt-2 text-primary" style="display: none;font-size:10px"></div>
            <div class="firm-phone-error alert-error mt-2 text-danger" style="display: none;font-size:10px"></div>

            @error('firm_phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group mb-3">
            <label for="firm_address" class="label-required fw-bold fs-3">Alamat Kantor</label>
            <textarea name="firm_address" id="firm_address" class="form-control @error('firm_address') is-invalid @enderror" rows="3">{{ old('firm_address') }}</textarea>

            @error('firm_address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group mb-3">
            <label for="branch_peradi" class="label-required fw-bold fs-3">Cabang PERADI</label>
            <input type="text" class="form-control @error('branch_peradi') is-invalid @enderror" id="branch_peradi" name="branch_peradi" value="{{ old('branch_peradi') }}">

            @error('branch_peradi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group mb-3">
            <label for="photo" class="label-required fw-bold fs-3">Pas Foto terbaru dengan Latar Belakang Merah</label>
            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" value="{{ old('photo') }}">

            @error('photo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="photo_ktp" class="label-required fw-bold fs-3">Foto KTP (khusus pindah domisili)</label>
            <input type="file" class="form-control @error('photo_ktp') is-invalid @enderror" id="photo_ktp" name="photo_ktp" value="{{ old('photo_ktp') }}">

            @error('photo_ktp')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="photo_ktp" class="label-required fw-bold fs-3">Foto KTPA</label>
            <input type="file" class="form-control @error('photo_ktp') is-invalid @enderror" id="photo_ktp" name="photo_ktp" value="{{ old('photo_ktp') }}">

            @error('photo_ktp')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label for="photo_ktp" class="label-required fw-bold fs-3">Foto ijazah legalisir basah (khusus penambahan gelar)</label>
            <input type="file" class="form-control @error('photo_ktp') is-invalid @enderror" id="photo_ktp" name="photo_ktp" value="{{ old('photo_ktp') }}">

            @error('photo_ktp')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
