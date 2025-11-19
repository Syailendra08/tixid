@extends('templates.app')

@section('content')
    <div class="container mt-5">
        <h5>Grafik Pembelian Tiket</h5>
    </div>
    <div class="row">
        <div class="col">
            <canvas id="chartBar"></canvas>
        </div>
    </div>
@endsection


@push('script')
    <script>
        let labelChartBar = null;
        let dataChartBar = null;
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
    </script>
@endpush
