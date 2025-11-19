@extends('templates.app')

@section('content')
<div class="container mt-5">
     @if (Session::get('success'))
            <div class="alert alert-success w-100 mt-3">{{ Session::get('success') }}</div>
        @endif
    <div class="d-flex justify-content-end">
        <a href="{{route('staff.schedules.index')}}" class="btn btn-secondary"> Kembali </a>
    </div>
     <h3 class="my-3">Data Sampah Jadwal Tayang</h3>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Bioskop</th>
                <th>Film</th>
                <th>Aksi</th>
            </tr>
            @foreach ($scheduleTrash as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- ambil detail data relasi dari with() : $item['namarelasi'] --}}
                    <td>{{ $schedule['cinema']['name'] }}</td>
                    <td>{{ $schedule['movie']['title'] }}</td>
                    <td class="d-flex align-item-center">
                        <form action="{{route('staff.schedules.restore', $schedule['id']) }}" method="POST" >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success  mx-2">Kembalikan</button>
                        </form>

                        <form action="{{route('staff.schedules.delete_permanent', $schedule['id']) }}" method="POST" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger  mx-2">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
</div>

@endsection
