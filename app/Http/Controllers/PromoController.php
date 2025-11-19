<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code',
            'discount' => 'required|numeric',
            'type' => 'required',

        ], [
            'promo_code.required' => 'Kode promo harus diisi',
            'promo_code.unique' => 'Kode promo sudah ada, silahkan gunakan kode lain',
            'discount.required' => 'Diskon harus diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'type.required' => 'Tipe diskon harus diisi',

        ]);

        $createPromo = Promo::create([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'actived' => 1
        ]);

        if ($createPromo) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil, promo berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('failed', 'Gagal, promo gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $promo = Promo::find($id);
        return view('staff.promo.edit', compact('promo'));
    
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $promo = Promo::find($id);
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code,'.$promo->id,
            'discount' => 'required|numeric',
            'type' => 'required',

        ], [
            'promo_code.required' => 'Kode promo harus diisi',
            'promo_code.unique' => 'Kode promo sudah ada, silahkan gunakan kode lain',
            'discount.required' => 'Diskon harus diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'type.required' => 'Tipe diskon harus diisi',

        ]);

        $updatePromo = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'actived' => 1,
        ]);


        if ($updatePromo) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil, promo berhasil diupdate!');
        } else {
            return redirect()->back()->with('failed', 'Gagal, promo gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteData = Promo::where('id', $id)->delete();
        if($deleteData) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil menghapus data promo!');
        }else {
            return redirect()->back()->with('error', 'Gagal menghapus data promo!');
        }
    }
    public function nonactived($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->actived = 0;
        $promo->save();

    return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil dinonaktifkan!');
    }
    public function export()
    {
        $fileName = 'data_promo.xlsx';
        return Excel::download(new PromoExport, $fileName);
    }

    public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $promoTrash = Promo::onlyTrashed()->get();
        return view ('staff.promo.trash', compact('promoTrash'));
    }
     public function restore($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengembalikan data!');
    }
    public function deletePermanent($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanent!');
     }
     public function datatables()
{
    $promos = Promo::query();

    return DataTables::of($promos)
        ->addIndexColumn()
        ->addColumn('actived_badge', function ($promo) {
            return $promo->actived
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Tidak Aktif</span>';
        })
        ->addColumn('action', function ($promo) {
            $btnEdit = '<a href="' . route('staff.promos.edit', $promo->id) . '" class="btn btn-primary mx-1">Edit</a>';
            $btnDelete = '<form action="' . route('staff.promos.destroy', $promo->id) . '" method="POST" style="display:inline-block" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\');">'
                . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-danger mx-1">Hapus</button>
            </form>';
            $btnNonActive = $promo->actived
                ? '<a href="' . route('staff.promos.nonactived', $promo->id) . '" class="btn btn-warning mx-1">Non Aktif</a>'
                : '';

            return '<div class="d-flex justify-content-center flex-wrap">'
                . $btnEdit . $btnDelete . $btnNonActive . '</div>';
        })
        ->rawColumns(['actived_badge', 'action'])
        ->make(true);
}
}
