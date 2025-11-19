@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5>Grafik Pembelian Tiket</h5>
    <div class="row">
        <div class="col-6">
            <h5>Pembelian tiket Bulan {{ now()->format('F') }}</h5>
            <canvas id="chartBar"></canvas>
        </div>
        <div class="col-6">
            <h5>Perbandingan Film Aktif dan Non-Aktif</h5>
            <canvas id="chartPie"></canvas>
        </div>
    </div>
    </div>
@endsection


@push('script')
    <script>
        let labelChartBar = null;
        let dataChartBar = null;
        let dataChartPie = null;
        // dijalankan ketika broser sudah generate kode htmlnya (pas di refresh)4
        $(function() {
            $.ajax({
                url: "{{ route('admin.tickets.chart') }}",
                method: "GET",
                success: function(response) {
                    labelChartBar = response.labels;
                    dataChartBar = response.data;
                    // panggil func untuk
                    showChart();
                },
                error: function(err) {
                    alert('Gagal mengambil data untuk chart tiket!');
                }
            });
            $.ajax({
                url: "{{ route('admin.movies.chart') }}",
                method: "GET",
                success: function(response) {
                    dataChartPie = response.data;
                    showChartPie();
                },
                error: function(err) {
                    alert('Gagal mengambil data untuk chart film!')
                }
            })
        });



        function showChart() {
            const ctx = document.getElementById('chartBar');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelChartBar,
                    datasets: [{
                        label: 'Pembelian tiket bulan ini',
                        data: dataChartBar,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function showChartPie() {
            const ctx2 = document.getElementById('chartPie');

            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Film Aktif', 'Film Tidak Aktif'
                    ],
                    datasets: [{
                        label: 'My First Dataset',
                        data: dataChartPie,
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        }
    </script>
@endpush
