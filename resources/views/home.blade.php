@extends('templates.app')

@section('content')
@if (Session::get('success'))
    {{--Auth::user(): mengambil data pengguna yg login--}}
    {{-- Format : Auth::user()->column_di_fillable--}}
    <div class="alert alert-success w-100">
        {{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b>
    </div>
@endif
@if (Session::get('logout'))

    <div class="alert alert-warning w-100">
        {{ Session::get('logout') }}
    </div>
@endif
<style>

.main-footer {
    background-color: #0d1a3c;
    color: #fff;
    padding: 40px 0;
    font-family: Arial, sans-serif;
    margin-top: 50px;
}


.main-footer .company-info .logo {
    max-height: 30px;
    margin-bottom: 15px;
}

.main-footer h3,
.main-footer h4 {
    color: #fff;
    margin-bottom: 15px;
}

.main-footer p {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 10px;
}

.container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-columns {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 30px;
}

.footer-section {
    flex: 1;
    min-width: 150px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-section li a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    line-height: 2.2;
    transition: color 0.3s;
}

.footer-section li a:hover {
    color: #007bff;
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icons li a {
    font-size: 20px;
}
</style>
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle w-100 d-flex align-items-center" type="button" id="dropdownMenuButton"
            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot me-2"></i>Bogor
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>
    <div id="carouselExampleIndicators" class="carousel slide" data-mdb-ride="carousel" data-mdb-carousel-init>
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://i.pinimg.com/736x/0e/84/d5/0e84d580eb24bffc3dabc017412d7a4d.jpg" class="d-block w-100"
                    style="height: 500px" alt="Wild Landscape" />
            </div>
            <div class="carousel-item">
                <img src="https://cdn.antaranews.com/cache/1200x800/2024/03/19/Teaser-Poster-Malam-Pencabut-Nyawa-Landscape.jpg"
                    class="d-block w-100" style="height: 500px" alt="Camera" />
            </div>
            <div class="carousel-item">
                <img src="https://preview.redd.it/made-a-landscape-poster-for-superman-as-well-credit-to-for-v0-c7eih5ce30vd1.jpeg?width=1080&crop=smart&auto=webp&s=aaca35b189b154563143ec1189b5f46536f67d31"
                    class="d-block w-100" style="height: 500px" alt="Exotic Fruits" />
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <div class="d-flex justify-content-between container mt-4">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-clapperboard"></i>
            <h5 class="mt-2">Sedang Tayang</h5>
        </div>
        <div>
            <a href="{{ route('home.movies') }}" class="btn btn-warning rounded-pill">Semua <i class="fa-solid fa-angle-right"></i></a>
        </div>

    </div>

    <div class="d-flex gap-2 container">
        <button type="button" class="btn btn-outline-primary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">Semua Film</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">XXI</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">Cinepolis</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">IMAX</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">CGV</button>
    </div>
    <div class="mt-3 d-flex justify-content-center container gap-2">
        @foreach ($movies as $movie)
        <div class="card shadow-sm" style="width: 15rem;">
            <img src="{{asset('storage/' . $movie['poster']) }}" class="card-img-top" style="height: 390px"
                alt="{{ $movie['title'] }}" />
            <div class="card-body text-center p-2" style="padding: 0 !important;">

               <p class="card-text text-center bg-primary py-2"><a href="{{route('schedules.detail', $movie->id)}}" class="text-warning"><b>Beli Tiket</b></a></p>
            </div>
        </div>
        @endforeach




    </div>


    <footer class="main-footer">
    <div class="container">
        <div class="footer-columns">
            <div class="footer-section company-info">
                <img src="https://asset.tix.id/wp-content/uploads/2021/10/TIXID_logo_inverse-300x82.png" alt="TIX ID Logo" class="logo">
                <p>Best App For Movie Lovers</p>
                <p>In Indonesia! Movie Entertainment Platform From Cinema To Online Movie Streaming Selections.</p>
            </div>

            <div class="footer-section nav-links">
                <h4>Now Showing</h4>
                <ul>
                    <li><a href="#">TIX NOW</a></li>
                    <li><a href="#">SPOTLIGHT</a></li>
                    <li><a href="#">VIDEO & TRAILERS</a></li>
                </ul>
            </div>

            <div class="footer-section site-links">
                <h4>Careers</h4>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                </ul>
            </div>

            <div class="footer-section social-links">
                <h4>TIX ID SUPPORT</h4>
                <p>E-MAIL: HELP@TIX.ID</p>
                <h4>FOLLOW US</h4>
                <ul class="social-icons">
                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-pinterest"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>


@endsection
