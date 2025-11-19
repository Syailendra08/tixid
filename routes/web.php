<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TicketController;
Route::get('/', [MovieController::class, 'home'])->name('home');
// Semua Data Film
Route::get('/home/movies', [MovieController::class, 'HomeAllMovies'])->name('home.movies');
// name: memberi identitas route untuk dipanggil
//path : kebab-case name : snake_casep

Route::get('/schedules/{movie_id}', [MovieController::class, 'movieSchedules'])->name('schedules.detail');

Route::middleware('isUser')->group(function() {
    // halaman pilihan kursi
    Route::get('/schedules/{scheduleId}/hours/{hourId}/show-seats', [TicketController::class, 'showSeats'])
    ->name('schedules.seats');
    Route::prefix('/tickets')->name('tickets.')->group(function() {
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticketId}/order', [TicketController::class, 'ticketOrder'])->name('order');
        Route::post('/payment', [TicketController::class, 'ticketPayment'])->name('payment');
        Route::get('/{ticketId}/payment', [TicketController::class, 'ticketPaymentPage'])->name('payment.page');
        Route::patch('/{ticketId}/payment/proof', [TicketController::class, 'paymentProof'])->name('payment.proof');
        Route::get('/{ticketId}/receipt', [TicketController::class, 'ticketReceipt'])->name('receipt');

    });
});



// menu bioskop pada navbar user (pengguna umum)
Route::get('/cinemas/list', [CinemaController::class, 'cinemaList'])->name('cinemas.list');
Route::get('/cinemas/{cinema_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinemas.schedules');

Route::middleware('isGuest')->group(function() {
   Route::get('/log-in', function () {
    return view('login');
})->name('log_in');
Route::post('/log-in', [UserController::class, 'loginAuth'])
    ->name('log_in.auth');

    Route::get('/sign-up', function () {
    return view('signup');
})->name('sign_up');
Route::post('/sign-up', [UserController::class, 'signUp'])
->name('sign_up.add');

});




//route -> controller - model - view : memerlukan data
// route - view : tidak memerlukan data

//get menampilkan halaman
//post mengambil data
// patch/put mengubah data
// delete menghapus data




Route::get('/logout', [UserController::class, 'logout'])
    ->name('logout');
Route::get('/', [MovieController::class, 'home'])->name('home');
    //prefix() :awalan, menulis /admin atau ma,e 'adminn' satu kali untuk 16 route CRUD
    // name('admin') : pake titik karena nanti akkkan digabungkan (admin.dashboard)

Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function() {
    Route::get('/tickets/chart', [TicketController::class, 'chartData'])->name('tickets.chart');
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::prefix('/cinemas')->name('cinemas.')->group(function() {
        Route::get('/', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', function() {
            return view('admin.cinemas.create');
        })->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('/export', [CinemaController::class, 'export'])->name('export');
         Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('delete-permanent/{id}',
        [CinemaController::class, 'deletePermanent'])->name('delete_permanent');
         Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');

    });

    Route::prefix('/users')->name('users.')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', function() {
            return view('admin.users.create');
        })->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('delete-permanent/{id}',
        [UserController::class, 'deletePermanent'])->name('delete_permanent');
         Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
    });

    Route::prefix('/movies')->name('movies.')->group(function() {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/nonactived', [MovieController::class, 'nonactived'])->name('nonactived');
        Route::get('/export', [MovieController::class, 'export'])->name('export');
         Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('delete-permanent/{id}',
        [MovieController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');

    });

});


Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function() {
    Route::get('/dashboard', function() {
        return view('staff.dashboard');
    })->name('dashboard');

    Route::prefix('/promos')->name('promos.')->group(function() {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/nonactived', [PromoController::class, 'nonactived'])->name('nonactived');
        Route::get('/export', [PromoController::class, 'export'])->name('export');
          Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('delete-permanent/{id}',
        [PromoController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    });
    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        Route::delete('delete-permanent/{id}',
        [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');





    });
});

