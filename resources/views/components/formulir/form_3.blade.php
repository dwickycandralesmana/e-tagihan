<div class="row">
    <div class="col-md-6 col-sm-12">
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
    <div class="col-md-6 col-sm-12">
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
    <div class="col-md-6 col-sm-12">
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
    <div class="col-md-6 col-sm-12">
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
</div>
