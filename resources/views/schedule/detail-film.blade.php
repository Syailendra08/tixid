@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <div class="w-75 d-block m-auto">
            {{-- Poster + Detail Film --}}
            <div class="d-flex">
                <div style="width: 150px; height: 200px">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="w-100 rounded">
                </div>
                <div class="ms-5 mt-4">
                    <h5 class="fw-bold">{{ $movie->title }}</h5>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><b class="text-secondary">Genre</b></td>
                            <td class="ps-3">{{ $movie->genre }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Durasi</b></td>
                            <td class="ps-3">{{ $movie->duration }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Sutradara</b></td>
                            <td class="ps-3">{{ $movie->director }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Rating Usia</b></td>
                            <td class="ps-3"><span class="badge bg-danger">{{ $movie->age_rating }}+</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center flex-column align-items-center">
            @php
                //request (/price) : ambil request, mengambil href+"?"

                if (request('price')) {
                    //digunakan untuk menagktifkan tab jadwal jika ada sortir/request price
                    $activeTab = true;
                    // kalau sudah pernah sortir price dantypenya ASC Ubah jadi DESC
                    if (request('price') == "ASC") {
                        $typePrice = "DESC";

                    } else {
                        $typePrice = "ASC";
                        // kalau sebelumnya bukan ASC (berarti DESC), type sortir jd ASC
                    }
                } else {
                    $activeTab = false;
                    $typePrice = "ASC";
                }
                @endphp
            <ul class="nav nav-underline" id="myTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == false ? ' active' : '' }}" id="home-tab" data-bs-toggle="tab" data-bs-target="#sinopsis-tab-pane"
                        type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Sinopsis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == true ? ' active' : '' }}" id="profile-tab" data-bs-toggle="tab" data-bs-target="#jadwal-tab-pane"
                        type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Jadwal</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade {{ $activeTab == false ? 'show active' : '' }}"
                id="sinopsis-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="mt-3 w-75 d-block mx-auto" style="text-align: justify">
                        {{ $movie['description'] }}
                    </div>
                </div>
                <div class="tab-pane fade {{ $activeTab == true ? 'show active' : '' }}" id="jadwal-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">

                    <div class="dropdown my-3 w-100">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton"
                            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                            Sortir
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            {{-- ? di href untuk mengieim data ke route GET. mengirim data price dengan isi ASC ke route lain--}}
                            <li><a class="dropdown-item" href="?price={{$typePrice}}-">Harga</a></li>
                            <li><a class="dropdown-item" href="">Alfabet</a></li>
                        </ul>
                    </div>
                    {{-- Ambil schedule dari relasi movie --}}
                    @foreach ($movie['schedules'] as $schedule)
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between">
                                {{-- ambil nama cinema dari relasi schedule --}}
                                <div><i class="fa-solid fa-building"></i> <b>{{ $schedule['cinema']['name'] }}</b></div>
                                <div>Rp. {{ number_format($schedule['price'], 0, ',','.') }}</div>
                            </div>
                            <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                            <br>
                            <div class="d-flex flex-wrap">
                                {{-- looping hours dari schedule --}}
                                @foreach ($schedule['hours'] as $index => $hours)
                                {{-- this: mengirimkan element html ke js untuk di manipulasi--}}
                                    <button class="btn btn-outline-secondary me-2" onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">{{ $hours }}</button>
                                @endforeach
                            </div>
                            <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="w-10 fixed-bottom bg-light text-center py-2" id="wrapBtn">
        {{--- javascript:void(0): nonaktifin href--}}
        <a href="javascript:void(0)" id="btnTiket">BELI TIKET</a>

    </div>
@endsection

@push('script')
<script>
    let btnBefore = null;
    function selectedHour(scheduleId, hourId, element) {
        // ada btnBefore (sebelumnya pernah di klik)
        if (btnBefore) {
            //ubah warna btn yg dikik sebelumnya ke abu abu lagi
            btnBefore.style.background = '';
            btnBefore.style.color = '';
            btnBefore.style.borderColor = '';
        }
        // warna biru ke btn yg diklik
        element.style.background = '#112646';
        element.style.color = 'white';
        //border-color jadi borderCOlor
        element.style.borderColor = '#112646';

        // update btnBefore ke element baru
        btnBefore = element;

        let wrapBtn = document.querySelector("#wrapBtn");
        let btnTiket = document.querySelector("#btnTiket");
        // warna biru di tulisan beli tiket
        wrapBtn.style.background = "#112646";
        //hapus class bg-light
        wrapBtn.classList.remove("bg-light");
        //text di href warna putih
        btnTiket.style.color = "white";

        // mengarahkan ke route web.php
        let url = "{{ route('schedules.seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}".replace(":scheduleId", scheduleId).replace(":hourId", hourId);
        btnTiket.href = url;
    }
</script>
@endpush
