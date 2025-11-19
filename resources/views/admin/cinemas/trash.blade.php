@extends('templates.app')

@section('content')
<div class="container mt-5">
     @if (Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
        {{Session::get('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="d-flex justify-content-end">
        <a href="{{route('admin.cinemas.index')}}" class="btn btn-secondary"> Kembali </a>
    </div>
    <h5>Data Sampah Bioskop</h5>
        <table class="table my-3 table-bordered">
            <tr>
                <th>No</th>
                <th class="text-center">Nama Bioskop</th>
                <th class="text-center">Detail Lokasi</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($cinemaTrash as $key => $cinema)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $cinema->name }}</td>
                <td>{{ $cinema->location }}</td>
                <td class="d-flex justify-content-center gap-2">
                    <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" method="post" >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Kembalikan</button>
                    </form>

                    <form action="{{ route('admin.cinemas.delete_permanent', $cinema->id) }}" method="post" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
</div>
@endsection
