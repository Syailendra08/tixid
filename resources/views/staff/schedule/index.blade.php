@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            {{-- modal add dimunculkan dengan bootstrap karena tidak memerlukan data dinamsi di modal --}}
           <a href="{{ route('staff.schedules.trash') }}" class="btn btn-secondary me-2">
    <i class="fa-solid fa-trash"></i> Data Sampah
</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success w-100 mt-3">{{ Session::get('success') }}</div>
        @endif
        <h3 class="my-3">Data Jadwal Tayang</h3>
        <table class="table table-bordered" id="scheduleTable">
            <tr>
                <th>#</th>
                <th>Bioskop</th>
                <th>Film</th>
                <th>Harga</th>
                <th>Jam Tayang</th>
                <th>Aksi</th>
            </tr>
            @foreach ($schedules as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- ambil detail data relasi dari with() : $item['namarelasi'] --}}
                    <td>{{ $schedule['cinema']['name'] }}</td>
                    <td>{{ $schedule['movie']['title'] }}</td>
                    <td>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</td>
                    <td>
                        {{-- karna hours array, akses dengan looping --}}
                        <ul>
                            @foreach ($schedule['hours'] as $hours)
                                {{-- bentuknya ['11.00', '12.00'] ga perlu key untuk akses --}}
                                <li>{{ $hours }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-flex">
                        <a href="{{route('staff.schedules.edit', $schedule['id'])}}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('staff.schedules.destroy', $schedule['id']) }}" method="POST" class="d-inline" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger  mx-2">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- modal --}}
        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddLabel">Tambah Data Jadwal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{-- agar membungkus sampe modal-footer ke button kirim --}}
                    <form method="POST" action="{{ route('staff.schedules.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                                <select type="text" name="cinema_id"
                                    class="form-select @error('cinema_id') is-invalid @enderror" id="cinema_id">
                                    <option disabled hidden selected>Pilih Bioskop</option>
                                    {{-- loop option sesuai data $cinemas --}}
                                    @foreach ($cinemas as $cinema)
                                        {{-- yg diambil id nya (value), yg dimunculin name nya --}}
                                        <option value="{{ $cinema['id'] }}">{{ $cinema['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="col-form-label">Film:</label>
                                <select name="movie_id" class="form-select @error('movie_id') is-invalid @enderror"
                                    id="movie_id">
                                    <option hidden disabled selected>Pilih Film</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie['id'] }}">{{ $movie['title'] }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga:</label>
                                <input type="number" name="price" id="price"
                                    class="form-control @error('price') is-invalid @enderror">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="hours" class="form-label">Jam Tayang</label>
                                <br>
                                {{-- karna hours array, err validasi diambil dengan : --}}
                                {{-- $errors->has() : jika dari err validasi ada err item hours --}}
                                @if ($errors->has('hours.*'))
                                    <br>
                                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                                @endif
                                <input type="time" name="hours[]" id="hours"
                                    class="form-control @if ($errors->has('hours.*')) is-invalid @endif">
                                {{-- sediakan tempat konten tambahan dari JS --}}
                                <div id="additionalInput"></div>
                                <span class="text-primary mt-2" style="cursor: pointer" onclick="addInput()">+ Tambah
                                    Jam</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- end of modal --}}
    </div>
@endsection

@push('script')
    <script>
        function addInput() {
            let content = `<input type="time" name="hours[]" class="form-control mt-2">`;
            // tempat konten akan ditambahkan
            let wrap = document.querySelector("#additionalInput");
            // karna nanti akan selalu bertambah, agar yg sebelumnya tidak hilang gunakan : +=
            wrap.innerHTML += content;
        }
    </script>
    {{-- pengecekan php, kalo ada error validasi apapun : $errors->any() --}}
    @if ($errors->any())
        <script>
            let modalAdd = document.querySelector("#modalAdd");
            // munculkan modal dengan JS
            new bootstrap.Modal(modalAdd).show();
        </script>
    @endif
    <script>
$(function () {
        $('#scheduleTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('staff.schedules.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'cinema_name', name: 'cinema.name' },
                { data: 'movie_title', name: 'movie.title' },
                { data: 'price', name: 'price' },
                { data: 'hours', name: 'hours', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });
    });
    </script>
@endpush
