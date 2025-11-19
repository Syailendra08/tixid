<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;
use App\Models\Schedule;
use Yajra\DataTables\Facades\DataTables;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        return view('admin.cinemas.index', compact('cinemas'));
        // compact -> argumen pada fungsi akan sama dengan nama variabel yang akan dikirim ke blade
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama bioskop harus diisi.',
            'name.min' => 'Nama wajib diisi minimal 3 huruf.',
            'location.required' => 'Lokasi Bioskop harus diisi.',
            'location.min' => 'Lokasi wajib diisi minimal 10 huruf.',
        ]);

        $createCinema = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($createCinema) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil membuat data bioskop!');
        }else {
            return redirect()->route('admin.cinemas.index')->with('error', 'Gagal membuat data bioskop!');
        }

    }




    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cinema = Cinema::find($id);
        return view('admin.cinemas.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama bioskop harus diisi.',
            'name.min' => 'Nama wajib diisi minimal 3 huruf.',
            'location.required' => 'Lokasi Bioskop harus diisi.',
            'location.min' => 'Lokasi wajib diisi minimal 10 huruf.',
        ]);
        $updateCinema = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if($updateCinema) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengubah data bioskop!');
        }else {
            return redirect()->back()->with('error', 'Gagal mengubah data bioskop!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedules = Schedule::where('cinema_id', $id)->count();
        if($schedules) {
            return redirect()->route('admin.cinemas.index')->with('failed', 'Tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');

        }
        $deleteData = Cinema::where('id', $id)->delete();
        if($deleteData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil menghapus data bioskop!');
        }else {
            return redirect()->back()->with('error', 'Gagal menghapus data bioskop!');
        }
    }
    public function export()
    {
        $fileName = 'data_bioskop.xlsx';
        return \Excel::download(new CinemaExport, $fileName);
    }
    public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $cinemaTrash = Cinema::onlyTrashed()->get();
        return view ('admin.cinemas.trash', compact('cinemaTrash'));
    }
     public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengembalikan data!');
    }
    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanent!');
     }
      public function datatables()
{
    $cinemas = Cinema::query();

    return DataTables::of($cinemas)
        ->addIndexColumn()
        ->addColumn('action', function ($cinema) {
            $btnEdit = '<a href="' . route('admin.cinemas.edit', $cinema->id) . '" class="btn btn-info me-1">Edit</a>';
            $btnDelete = '<form action="' . route('admin.cinemas.delete', $cinema->id) . '" method="POST" style="display:inline-block" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">'
                . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-danger me-1">Hapus</button>
            </form>';
            return '<div class="d-flex justify-content-center align-items-center flex-wrap">'
                . $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
}

    public function cinemaList() {
        $cinemas =Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

    public function cinemaSchedules($cinema_id)
    {
        $schedules = Schedule::where('cinema_id', $cinema_id)->with('movie')->whereHas('movie', function($q) {
            $q->where('actived', 1);
        })->get();
        return view('schedule.cinemas-schedules', compact('schedules'));
    }

}
