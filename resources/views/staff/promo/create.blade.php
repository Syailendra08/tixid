@extends('templates.app')
@section('content')
<div class="w-75 mx-auto my-5">
    <form action="{{ route('staff.promos.store') }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-6">
                <label for="promo_code" class="form-label">Kode Promo</label>
                <input type="text" name="promo_code" id="promo_code"
                       class="form-control @error('promo_code') is-invalid @enderror"
                       value="{{ old('promo_code') }}">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-6">
                <label for="discount" class="form-label">Diskon</label>
                <input type="number" name="discount" id="discount"
                       class="form-control @error('discount') is-invalid @enderror"
                       value="{{ old('discount') }}">
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="type" class="form-label">Tipe Diskon</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="" disabled selected>Pilih tipe</option>
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Persen (%)</option>
                    <option value="rupiah" {{ old('type') == 'rupiah' ? 'selected' : '' }}>Rupiah (Rp)</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>


        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>
@endsection
