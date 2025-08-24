<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\JenjangController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('login/otp', [HomeController::class, 'otp'])->name('login.otp');
Route::get('tagihan/{id}/pdf', [TagihanController::class, 'pdf'])->name('tagihan.pdf');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::delete('tagihan/{id}/destroy', [TagihanController::class, 'destroy'])->name('tagihan.destroy');
    // Route::get('tagihan/{id}/show', [TagihanController::class, 'show'])->name('tagihan.show');
    // Route::get('tagihan', [TagihanController::class, 'tagihan'])->name('tagihan.index');

    Route::post('tagihan/import-tunggakan', [TagihanController::class, 'importTunggakan'])->name('tagihan.import-tunggakan');
    Route::post('tagihan/import', [TagihanController::class, 'import'])->name('tagihan.import');
    Route::get('tagihan/data', [TagihanController::class, 'data'])->name('tagihan.data');
    Route::resource('tagihan', TagihanController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('user/profile-settings', [UserController::class, 'profile_update'])->name('user.profile_update');
    Route::get('user/profile-settings', [UserController::class, 'profile'])->name('user.profile');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('pembayaran/{id}/pdf', [PembayaranController::class, 'pdf'])->name('pembayaran.pdf');
    Route::get('pembayaran/data', [PembayaranController::class, 'data'])->name('pembayaran.data');
    Route::resource('pembayaran', PembayaranController::class);

    Route::get('jenis-pembayaran/data', [JenisPembayaranController::class, 'data'])->name('jenis-pembayaran.data');
    Route::resource('jenis-pembayaran', JenisPembayaranController::class);

    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('siswa/data', [SiswaController::class, 'data'])->name('siswa.data');
    Route::resource('siswa', SiswaController::class);

    Route::post('user/import', [UserController::class, 'import'])->name('user.import');
    Route::get('user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);

    Route::get('jenjang/{id}/export', [JenjangController::class, 'export'])->name('jenjang.export');
    Route::get('jenjang/data', [JenjangController::class, 'data'])->name('jenjang.data');
    Route::resource('jenjang', JenjangController::class);

    Route::post('setting/bulk', [SettingController::class, 'bulk'])->name('setting.bulk');
    Route::get('setting/data', [SettingController::class, 'data'])->name('setting.data');
    Route::resource('setting', SettingController::class);
});

require __DIR__ . '/auth.php';
