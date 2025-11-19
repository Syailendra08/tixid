@extends('templates.app')

@section('content')
@if (Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
        {{Session::get('success')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
<div class="container mt-3">

        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
 <div class="d-flex justify-content-end mb-3 mt-4">

            <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">
    <i class="fa-solid fa-trash"></i> Data Sampah
</a>
            <a href="{{ route('admin.users.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{route('admin.users.create')}}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5>Data Pengguna (Admin & Staff)</h5>
        <table class="table my-3 table-bordered" id="userTable">
            <tr>
                <th></th>
                <th class="text-center">Nama </th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($users as $key => $user)
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
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

@endsection
@push('script')
<script>
$(function() {
    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.datatables')}}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    })
})
</script>
@endpush
