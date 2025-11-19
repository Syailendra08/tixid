@extends('templates.app')

@section('content')
<div class="container mt-5">
    @if (Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
        {{Session::get('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="d-flex justify-content-end">
        <a href="{{route('admin.movies.index')}}" class="btn btn-secondary"> Kembali </a>
    </div>
    <h5 class="mt-3">Data Sampah Film</h5>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @foreach ($movieTrash as $key => $item)
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
                         <form action="{{ route('admin.movies.restore', $item['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary mx-2">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.movies.delete_permanent', $item['id']) }}" method="POST">
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
</div>
@endsection
