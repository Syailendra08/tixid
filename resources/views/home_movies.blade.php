@extends('templates.app')

@section('content')
    <div class="container mt-5">
        {{--Form search gunakan method GET karena form manggil data
        bukan nyimpen data, Actionnya kosong untuk diarahkan ke proses yg sama (tetap disini) --}}
        <form action="" method="GET">
            @csrf
            <div class="row">
                <div class="col-10">
                    <input type="text" name="search_movie" placeholder="Cari Judul Film...."
                    class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div class="mt-3 d-flex justify-content-center container gap-2">
            @foreach ($movies as $movie)
                <div class="card shadow-sm" style="width: 15rem;">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" alt="{{ $movie['title'] }}";
                        style="height: 300px; abject-fit: cover;" />
                    <div class="card-body text-center p-2" style="padding: 0 !important;">

                        <p class="card-text text-center bg-primary py-2"><a href="{{ route('schedules.detail', $movie['id']) }}"
                                class="text-warning"><b>Beli Tiket</b></a></p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
