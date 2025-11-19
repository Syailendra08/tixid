<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
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
        'email' => 'required|email:dns',
        'password' => 'required|min:8',

    ], [
        'name.required' => 'Nama wajib diisi.',
        'name.min' => 'Nama wajib diisi minimal 3 huruf.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Email wajib diisi dengan data yang valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password wajib diisi minimal 8 karakter.',
    ]
);

    $createUser = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'staff', // staff/admin
    ]);
   if ($createUser) {
    return redirect()->route('admin.users.index')->with('success', 'Berhasil, pengguna berhasil ditambahkan!');
} else {
    return redirect()->route('admin.users.index')->with('error', 'Gagal, pengguna gagal ditambahkan!');
}


}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'required|min:8',

        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama wajib diisi minimal 3 huruf.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email wajib diisi dengan data yang valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password wajib diisi minimal 8 karakter.',
        ]
        );

        $updateUser = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff'
        ]);
        if ($updateUser) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil, Staff berhasil diupdate!');
        } else {
            return redirect()->route('admin.users.index')->with('error', 'Gagal, Staff gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        {
        $deleteData = User::where('id', $id)->delete();
        if($deleteData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data pengguna!');
        }else {
            return redirect()->back()->with('error', 'Gagal menghapus data pengguna!');
        }
    }
    }
    public function signUp(Request $request)
    {
        // (request $request Class untukk mengambil value dari form
        //validasi
        $request->validate([
            //name_input => 'tipe validasi'
            // required wajib diisi, min : minimal karakter (teks)
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            //email:dns emailnya valid @gmai..
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ], [
            //pesarn eror custom
           // 'name_input.validasi => 'pesan'
           'first_name.required' => 'Nama depan wajib diisi.',
           'first_name.min' => 'Nama depan wajib diisi minimal 3 huruf.',
           'last_name.required' => 'Nama belakang wajib diisi.',
           'last_name.min' => 'Nama belakang wajib diisi minimal 3 huruf.',
           'email.required' => 'Email wajib diisi.',
           'email.email' => 'Email wajib diisi dengan data yang valid.',
           'password.required' => 'Password wajib diisi.',
           'password.min' => 'Password wajib diisi minimal 8 karakter.',
        ]);
        //Create() membaut data baru
        $createUser = User::create([
            //'nama_column' => $request->nama_imput
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            //Hash : enskripsi data (mengubah menjadi karatker acak) agar tdk ada yg bisa nambah isinya
            'password' =>  Hash::make($request->password),
            //Pengguna tdk bisa memilih role (akses), jadi manual ditambahkan user
            'role' => 'user'
        ]);
        if ($createUser) {
            //Redirect memindahkan halaman, route() :nama routing yang dituju
            //with() : mengirim session, biasanya unuk notifikasi
            return redirect()->route('log_in')->with('success', 'Silahkan login!');
        } else {
            //back() : kembali ke halaman sebelumnya
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
        }
    }
    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);
        //mengambil data yang akan diverifikasi
        $data = $request->only('email', 'password');
        //Auth:: -. class laravel utk penaganan autentikasi
        //attempt() -> method class auth untuk  mencocokan email-pw atau username-pw
        //kalau cocok akan disimpan datanya ke session auth
        if (Auth::attempt($data)) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success',
                'Berhasil Login');

            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success',
                'Berhasil Login');
            }else {
                  return redirect()->route('home')->with('success', 'Berhasil Login');
            }

        } else {
            return redirect()->back()->with('error', 'Gagal login! pastikan email dan
             password sesuai');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Logout berhasil!
         silahkan login kembali untuk akses lengkap');
    }
    public function export()
    {
        $fileName = 'data_pengguna.xlsx';
        return Excel::download(new UserExport, $fileName);
    }
    public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $userTrash = User::onlyTrashed()->get();
        return view ('admin.users.trash', compact('userTrash'));
    }
     public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }
    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanent!');
     }
     public function datatables() {
        $users = User::query();

        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('role', function($user) {
            if ($user->role == 'admin') {
                return '<span class="badge bg-primary">Admin</span>';
            } elseif ($user->role == 'staff') {
                return '<span class="badge bg-success">Staff</span>';
            } else {
                return '<span class="badge bg-secondary">User</span>';
            }
        })
        ->addColumn('action', function($user) {
            $btnEdit = '<a href="'. route('admin.users.edit', $user->id) .'" class="btn btn-sm btn-warning ">Edit</a>';
            $btnDelete = '<form action="'. route('admin.users.destroy', $user->id) . '" method="POST" class="btn  me-1">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>';

            return '<div class="d-flex justify-content-center align-items-center flex-wrap">
                ' . $btnEdit . $btnDelete . '
            </div>';
        })
        ->rawColumns(['role', 'action'])
        ->make(true);
     }
}
