@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">
    <i class="fa-solid fa-trash"></i> Data Sampah
</a>
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Film</h5>
        <table class="table table-bordered" id="moviesTable">
            <tr>
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @foreach ($movies as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>

                        <img src="{{ asset('storage/' . $item['poster']) }}" width="120">
                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td>
                        @if ($item['actived'] == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center">
                        {{-- onclick : menjalankan fungsi js ketika komponen di klik --}}
                        <button class="btn btn-secondary me-2" onclick="showModal({{ $item }})">Detail</button>
                        <a href="{{ route('admin.movies.edit', $item['id']) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.movies.destroy', $item['id']) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-2">Hapus</button>
                        </form>
                        @if ($item['actived'] == 1)
                            <a href="{{ route('admin.movies.nonactived', $item['id']) }}" class="btn btn-warning ">Non Aktif</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Film</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    function showModal(item) {
        let image = "{{ asset('storage/') }}/" + item.poster;
        let content = `
            <div class="d-block mx-auto my-2">
                <img src="${image}" width="120" alt="Poster">
            </div>
            <ol>
                <li>Judul: ${item.title}</li>
                <li>Durasi: ${item.duration}</li>
                <li>Genre: ${item.genre}</li>
                <li>Sutradara: ${item.director}</li>
                <li>Usia Minimal: <span class="badge bg-danger">${item.age_rating}+</span></li>
                <li>Sinopsis: ${item.description}</li>
            </ol>
        `;
        document.querySelector("#modalDetailBody").innerHTML = content;
        new bootstrap.Modal(document.querySelector("#modalDetail")).show();
    }

    $(function() {
        $('#moviesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.movies.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'poster_img', name: 'poster_img', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'actived_badge', name: 'actived_badge', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
