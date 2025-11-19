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
         <a href="{{route('staff.promos.index')}}" class="btn btn-secondary"> Kembali </a>
    </div>
     <h5>Data Promo</h5>
        <table class="table my-3 table-bordered">
            <tr>
                <th></th>
                <th class="text-center">Kode Promo </th>
                <th class="text-center">Diskon</th>
                <th class="text-center">Tipe</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($promoTrash as $key => $promo)
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
                    <form action="{{ route('staff.promos.restore', $promo['id']) }}" method="POST" >
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-secondary  mx-2">Kembalikan</button>
                    </form>
                    <form action="{{ route('staff.promos.delete_permanent', $promo['id']) }}" method="POST" >
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
