@extends('templates.app')

@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 10% !important">
        <div class="card-body">
            <div>
                <b>{{ $schedule['cinema']['name'] }}</b>
                {{-- now() : amvil tgl hari ini, format d (tgl) F (nama bulan) Y (tahun) --}}
                <br>
                <b>{{ now()->format('d F, Y') }} || {{ $hour }}</b>
            </div>
            <div class="alert my-2 alert-secondary">
                <i class="fa-solid fa-info text-danger"></i> Anak berusia 2 tahun keatas wajib membeli tiket.
            </div>
            <div class="d-flex justify-content-center">
                <div class="row w-75">
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: #112646"></div>Kursi Tersedia
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: #eaeaea"></div>Kursi Terjual
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; background: blue"></div>Kursi dipilih
                    </div>
                </div>
            </div>
            @php
                //membuat data A-H baris kursi
                $row = range('A', 'H');
                // membuat data 1-18 nomor kursi
                $col = range(1,18);
            @endphp
            {{-- looping baris A - H --}}
            @foreach ($row as $baris)
            <div class="d-flex justify-content-center">
                {{-- LOOOPING angka kursi --}}
                @foreach ($col as $nomorKursi)
                {{-- jika kursi no 7 kasi space kosong untuk jalan kursi--}}
                @if ($nomorKursi == 7)
                <div style="width: 55px;"></div>
                @endif
                <div style="background: #112646; color: white; text-align: center; padding-top: 10px; width: 45px; height: 45px;
                border-radius: 10px; margin: 10px 3px; cursor: pointer"
                onclick="selectedSeats('{{ $schedule->price }}', '{{ $baris }}', '{{ $nomorKursi }}',
                this)">
                    {{ $baris }}-{{ $nomorKursi }}
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom w-100 bg-light text-center pt-4">
        <b class="text-center">LAYAR BIOSKOP</b>
        <div class="row mt-4" style="border: 1px solid #eaeaea">
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea">
                <h5>Total Harga</h5>
                <h5 id="totalPrice">Rp. -</h5>
            </div>
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea">
                <h5>Tempat Duduk</h5>
                <h5 id="seats">belum dipilih</h5>
            </div>
        </div>
        {{-- input hidden yg disembunyikan hanya untuk menyinpan nilai yg diperlukan JS untuk tambah data ticket--}}
        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id }}">
        <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="hours" id="hours" value="{{ $hour }}">

        <div class="text-center p-2 w-100 " style="cursor: pointer" id="btnOrder"><b>RINGKASAN ORDER</b></div>
    </div>
@endsection

@push('script')
    <script>
    // menyimpan data kursi yang dipilih
    let seats = [];
    let totalPrice = 0;

    function selectedSeats(price, baris, nomorKursi, element) {
        // buat A-1
        let seat = baris + "-" + nomorKursi;
        // cek apakah kursi ini sudah dipilih sebeumnya, cek dari apakah ada di array seats diatas atau enggak jika ada kembalikan indexnya (index0f)
        let indexSeat = seats.indexOf(seat);
        // jika tidak ada berarti kursi baru dipilih, kalo gaada index nya -1
        if (indexSeat == -1) {
            // kalo gaada kasi warna biru terang dan simpan data kursi ke array diatas
            element.style.background = "blue";
            seats.push(seat);
        } else {
            // jika ada, berarti ini klik kedua kali di kursi tsb.  kembalikan warna ke biru tua dan hapus item dari array
            element.style.background = "#112646";
            seats.splice(indexSeat, 1);
        }

        let totalPriceElement = document.querySelector("#totalPrice");
        let seatsElement = document.querySelector("#seats");
        // hitung harga dari parameter dikali jumlah kuersi yg dipilih
        totalPrice = price * (seats.length); //length : menghitung jumlah item array
        // simpan harga di element html
        totalPriceElement.innerText = "Rp. " + totalPrice;
        // join(', ') : mengubah array menjadi string dipisahkan tanda tertentu
        seatsElement.innerText = seats.join(", ")

        let btnOrder =document.querySelector('#btnOrder');
        // seats array isinya lebih dari sama dengan satu, aktifin btn order
        if (seats.length >= 1) {
            btnOrder.style.background = '#112646';
            btnOrder.style.color = 'white';
            // buat agar ketika di klirk mengarah ke  createTicket
            btnOrder.onclick = createTicket;
        } else {
            btnOrder.style.background = '';
              btnOrder.style.color = '';
        }
    }

    function createTicket() {
    //Ajax (Asynchronus Javascript dan XML) : proses mengambil/menambahkan data dari/kedatabase. hanya bisa digunakan melalui Jquery (library yg penullisannya berupa js modern dan singkat $()
    $.ajax({
        url: "{{route('tickets.store') }}", //route untuk proses data
        method: "POST", //http method sesuaii url
        data: {
            // data yg mau dikirim ke route (kalo di html, input form)
            _token: "{{ csrf_token() }}",
            user_id: $("#user_id").val(), //value+"" dr input id="user_id"
            schedule_id: $("#schedule_id").val(),
            hours: $("#hours").val(),
            quantity: seats.length, // jumlah item array seats
            total_price: totalPrice,
            rows_of_seats: seats,
            //fillable : value
        },
        success: function(response) { //kalau berhasil, mau ngapain. data hasil disimpan di respone
       // console.log(response)
       // redirect JS : window.location.href
       // response messae & data
            let ticketId = response.data.id;
            window.location.href = `/tickets/${ticketId}/order`;
        },
        error: function(message) { //kalau diservernya ada error mau ngapain
        alert("Terjadi kesalahan ketika membuat data ticket!");
        }
    })
}
    </script>
    @endpush
