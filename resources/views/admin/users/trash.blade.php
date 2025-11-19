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
        <a href="{{route('admin.users.index')}}" class="btn btn-secondary"> Kembali </a>
    </div>
    <h5>Data Sampah Pengguna</h5>
        <table class="table my-3 table-bordered">
            <tr>
                <th></th>
                <th class="text-center">Nama </th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($userTrash as $key => $user)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">
    @if ($user->role == 'admin')
        <span class="badge badge-primary">admin</span>
    @elseif ($user->role == 'staff')
        <span class="badge badge-success">staff</span>
    @else
        <span class="badge badge-secondary">{{ $user->role }}</span>
    @endif
</td>
                <td class="text-center">
                   <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Kembalikan</button>
                    </form>
                    <form action="{{ route('admin.users.delete_permanent', $user->id) }}" method="POST" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger ">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
</div>
@endsection
