@extends('templates.app')

@section('content')
    <div class="container my-5">
        <form method="POST" action="{{ route('staff.schedules.update', $schedule['id']) }}">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                {{-- dibuat disabled agar tidak dapat diubah --}}
                <input type="text" name="cinema_id" class="form-control" disabled id="cinema_id"
                    value="{{ $schedule['cinema']['name'] }}">
                </input>
                @error('movie_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="movie_id" class="col-form-label">Film:</label>
                <input type="text" name="movie_id" class="form-control" disabled id="movie_id"
                    value="{{ $schedule['movie']['title'] }} disabled">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga:</label>
                <input type="number" name="price" id="price"
                    class="form-control @error('price') is-invalid @enderror" value="{{ $schedule['price'] }}">
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">

                <label for="hours" class="form-label">Jam Tayang</label>

                @foreach ($schedule['hours'] as $index => $hours)
                    <div class="d-flex align-content-center hour-item">
                        <input type="time" name="hours[]" id="hours" class="form-control my-2"
                            value="{{ $hours }}">
                        {{-- this.closest() : mencari element dengan class hour-item yang paling dekat dari element yang di klik, kemudian remove() : hapus --}}
                        @if ($index > 0)
                            <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 1.5rem; cursor: pointer;"
                                onclick="this.closest('.hour-item').remove()"></i>
                        @endif

                    </div>
                @endforeach

                {{-- wadah buat content JS --}}

                <div id="additionalInput"></div>

                <span class="text-primary mt-2" style="cursor: pointer" onclick="addInput()">+ Tambah Jam</span>

                {{-- karna hours array, err validasi diambil dengan : --}}

                {{-- $errors->has() : jika dari err validasi ada err item hours --}}

                @if ($errors->has('hours.*'))
                    <br>

                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                @endif

            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
@push('script')
    <script>
        function addInput() {
            let content = `<div class="d-flex align-content-center hour-additional">
                <input type="time" name="hours[]" id="hours"  class="form-control  is-invalid">
                <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 1.5rem; cursor: pointer;"
            onclick="this.closest('.hour-additionalInput').remove()" > </i>

                </div>`;
            document.querySelector("#additionalInput").innerHTML += content;

        }
    </script>
@endpush
