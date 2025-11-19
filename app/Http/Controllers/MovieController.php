<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use Yajra\DataTables\Facades\DataTables;




class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));

    }

    public function chartData()
    {
        $movieActive = Movie::where('actived', 1)->count();
        $movieNonActive = Movie::where('actived', 0)->count();
        // karena chart hanya perlu jumlah, jd htiung dengan count();
        $data = [$movieActive, $movieNonActive];
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required|numeric',
            'poster' => 'required|mimes:jpg,png,jpeg,webp,svg',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul Film harus diisi',
            'duration.required' => 'Durasi Film harus diisi',
            'genre.required' => 'Genre Film harus diisi',
            'director.required' => 'Sutradara Film harus diisi',
            'age_rating.required' => 'Usia Minimal penonton harus diisi',
            'age_rating.numeric' => 'Usia Minimal penonton harus berupa angka',
            'poster.required' => 'Poster Film harus diisi',
            'poster.mimes' => 'Format poster harus berupa JPG/JPEG/PNG/WEBP/SVG',
            'description.required' => 'Sinopsis Film harus diisi',
            'description.min' => 'Sinopsis Film harus diisi minimal 10 karakter'
        ]);
        // ambil file yang di upload = $request-.file('poster')
        $gambar = $request->file('poster');
        // buat nama baru di filmnya, agar mengindar nama file yang sama
        //nama file yang diinginkan = <random>-poster.png
        // GetClientOriginalExtension() : mengambil ekstensi dari file yang diupload(png/jpg/jpeg)
        $namaGambar = Str::random(5) . '-poster.' . $gambar->getClientOriginalExtension();
        // simpan file ke storage, nama file gunakan nama file baru
        // storeAs ('namaFolder', 'namaFile', 'public') : format menyimpan file
        $path = $gambar->storeAs('poster', $namaGambar , 'public');

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path, //path berisi lokasi file yg disimpan
            'description' => $request->description,
            'actived' => 1 //1 = aktif, 0 = tidak aktif
        ]);
        if($createData) {
            return redirect()->route('admin.movies.index')
                ->with('success', 'Berhasil membuat data film');
        } else {
            return redirect()->back()->with('error', 'Gagal! Silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required|numeric',
            'poster' => 'mimes:jpg,png,jpeg,webp,svg',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul Film harus diisi',
            'duration.required' => 'Durasi Film harus diisi',
            'genre.required' => 'Genre Film harus diisi',
            'director.required' => 'Sutradara Film harus diisi',
            'age_rating.required' => 'Usia Minimal penonton harus diisi',
            'age_rating.numeric' => 'Usia Minimal penonton harus berupa angka',
            'poster.mimes' => 'Format poster harus berupa JPG/JPEG/PNG/WEBP/SVG',
            'description.required' => 'Sinopsis Film harus diisi',
            'description.min' => 'Sinopsis Film harus diisi minimal 10 karakter'
        ]);
        // data sebelumnya
        $movie = Movie::find($id);
        if ($request->file('poster')) {
            //storage_path('app/public/' . $movie->poster) : cek apakah file ada di storage
            $filSebelumnya = storage_path('app/public/' . $movie->poster);
            if(file_exists($filSebelumnya)) {
                //hapus file lama
                unlink($filSebelumnya);
            }

            // ambil file yang di upload = $request-.file('poster')
            $gambar = $request->file('poster');
            // buat nama baru di filmnya, agar mengindar nama file yang sama
            //nama file yang diinginkan = <random>-poster.png
            // GetClientOriginalExtension() : mengambil ekstensi dari file yang diupload(png/jpg/jpeg)
            $namaGambar = Str::random(5) . '-poster.' . $gambar->getClientOriginalExtension();
            // simpan file ke storage, nama file gunakan nama file baru
            // storeAs ('namaFolder', 'namaFile', 'public') : format menyimpan file
            $path = $gambar->storeAs('poster', $namaGambar , 'public');
        }


        $updateData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // ?? sebelum ?? (if) setelah ?? (eelse)
            // kalau ada $path (poster baru), ambil data baru. kalau tidak ada, ambil dr data $movie sebelumnya
            'poster' => $path ?? $movie['poster'], //path berisi lokasi file yg disimpan
            'description' => $request->description,
            'actived' => 1 //1 = aktif, 0 = tidak aktif
        ]);
        if($updateData) {
            return redirect()->route('admin.movies.index')
                ->with('success', 'Berhasil mengubah data film');
        } else {
            return redirect()->back()->with('error', 'Gagal! Silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $schedules = Schedule::where('movie_id', $id)->count();
        if($schedules) {
            return redirect()->route('admin.movies.index')->with('failed', 'Tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');

        }
         $film = Movie::find($id);

    if ($film) {
        if ($film->poster && Storage::disk('public')->exists($film->poster)) {
            Storage::disk('public')->delete($film->poster);
        }

        $deleteData = $film->delete();

        if ($deleteData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus data film!');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data film!');
        }
    }


    }

    public function home()
    {
        // mengurutkan -> orderBy('created_at', 'DESC') // ASC: A-z, 0-9 DESC: Z-A, 9-0
        // limit(4) : membatasi data yang diambil hanya 4 data

        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function nonactived($id)
{
    $film = Movie::findOrFail($id);


    $film->actived = 0;
    $film->save();

    return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dinonaktifkan!');
}

public function export()
{
    // nama file saat di download
    $fileName = 'data-Film.xlsx';
    // proses
    return Excel::download(new MovieExport, $fileName);
}

    public function HomeAllMovies(Request $request)
    {
        // ambil value input search name+'"search_movie"
        $title = $request->search_movie;
        // Cek jika input search ada isinya maka cari data
        if($title != "") {
            //LIKE : mencari data yang mengandung kata tertentu
            // % depan  : mencari kata belakang, $ belakang : mencari kata depan, % depan belakang mencari kataa didepan dan belakang
            $movies = Movie::where('title', 'LIKE', '%' .$title. '%')->where('actived',
             1)->orderBy('created_at', 'DESC')->get();
        } else {
            $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();

        }
        return view('home_movies', compact('movies'));
    }

     public function movieSchedules($movie_id, Request $request)
    {
        // ambil dataa dari href="?price=ASC" tanda tanyta
        $priceSort = $request->price;

        // ambil data film beserta schedule dan bioskop pada schedule
        if($priceSort) {
            /// kaerna price adanya di schedule bukan movie, jadi urutkan datanya dari schedule (re;asi)
            $movie = Movie::where('id', $movie_id)->with(['schedules' => function
            ($q) use ($priceSort) {
                // 'schedules' => function: melakukan filter pada relasi
                // $q yang mewakilkan model schedule
                $q->orderBy('price', $priceSort);

            }, 'schedules.cinema'])->first();
        }else {
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }
        return view('schedule.detail-film', compact('movie'));
    }
    public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $movieTrash = Movie::onlyTrashed()->get();
        return view ('admin.movie.trash', compact('movieTrash'));
    }
     public function restore($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }
    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanent!');
     }

    public function datatables()
    {
        $movies = Movie::query();

return DataTables::of($movies)
    ->addIndexColumn()
    ->addColumn('poster_img', function ($movie) {
        $url = asset('storage/' . $movie->poster);
        return '<img src="' . $url . '" width="70">';
    })
    ->addColumn('actived_badge', function ($movie) {
        return $movie->actived
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Non-Aktif</span>';
    })
    ->addColumn('action', function ($movie) {
        $btnDetail = '<button type="button" class="btn btn-secondary me-1" onclick=\'showModal(' . json_encode($movie) . ')\'>Detail</button>';
        $btnEdit = '<a href="' . route('admin.movies.edit', $movie->id) . '" class="btn btn-primary me-1">Edit</a>';
        $btnDelete = '
            <form action="' . route('admin.movies.destroy', $movie->id) . '" method="POST" style="display:inline-block">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-danger me-1">Hapus</button>
            </form>
        ';

        return '
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                ' . $btnDetail . $btnEdit . $btnDelete . '
            </div>
        ';
    })
    ->rawColumns(['poster_img', 'actived_badge', 'action'])
    ->make(true);
    }
}
