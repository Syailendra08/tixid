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
     <a href="{{ route('staff.promos.trash') }}" class="btn btn-secondary me-2">
    <i class="fa-solid fa-trash"></i> Data Sampah
</a>
            <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5>Data Promo</h5>
        <table class="table my-3 table-bordered" id="promoTable">
            <tr>
                <th></th>
                <th class="text-center">Kode Promo </th>
                <th class="text-center">Diskon</th>
                <th class="text-center">Tipe</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($promos as $key => $promo)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $promo['promo_code'] }}</td>
                <td>{{ $promo['discount'] }}</td>
                <td>{{ $promo['type'] }}</td>
                <td class="text-center"> @if ($promo['actived'] == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif

</td>
                <td class="text-center">
                    <a href="{{ route('staff.promos.edit', $promo['id']) }}" class="btn btn-primary  mx-2">Edit</a>
                    <form action="{{ route('staff.promos.destroy', $promo['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger  mx-2">Hapus</button>
                    </form>
                    @if ($promo['actived'] == 1)
                            <a href="{{ route('staff.promos.nonactived', $promo['id']) }}" class="btn btn-warning ">Non Aktif</a>
                        @endif

                </td>
            </tr>
 @endforeach
        </table>
    </div>

@endsection

@push('script')
<script>
$(function() {
    $('#promoTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('staff.promos.datatables') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'promo_code', name: 'promo_code' },
            { data: 'discount', name: 'discount' },
            { data: 'type', name: 'type' },
            { data: 'actived_badge', name: 'actived_badge', orderable: false, searchable: false, className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });
});
</script>
@endpush
