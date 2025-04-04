<?php

use App\Models\SumberBayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RamController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\SupirController;
use App\Http\Controllers\PabrikController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\UserPabrikController;
use App\Http\Controllers\SumberBayarController;
use App\Http\Controllers\TimbangBeliController;
use App\Http\Controllers\TimbangNotaController;
use App\Http\Controllers\TimbangTiketController;
use App\Http\Controllers\BerangkatMobilController;
use App\Http\Controllers\BerangkatSupirController;
use App\Http\Controllers\BerangkatPabrikController;
use App\Http\Controllers\BerangkatTimbangController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth-cek', [AuthController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('token-cek/{grup_id}', [AuthController::class, 'tokenGrupCek']);

    Route::resource('grup', GrupController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('ram', RamController::class);
    Route::resource('pabrik', PabrikController::class);
    Route::resource('supir', SupirController::class);
    Route::resource('mobil', MobilController::class);
    Route::resource('timbang-tiket', TimbangTiketController::class);
    Route::resource('timbang-beli', TimbangBeliController::class);
    Route::resource('timbang-nota', TimbangNotaController::class);
    Route::resource('sumber-bayar', SumberBayarController::class);

    Route::resource('timbang-berangkat', BerangkatTimbangController::class);
    Route::resource('supir-berangkat', BerangkatSupirController::class);
    Route::resource('mobil-berangkat', BerangkatMobilController::class);
    Route::resource('pabrik-berangkat', BerangkatPabrikController::class);

    Route::resource('user', AkunController::class);
    Route::get('daftar-akses/{id}', [AkunController::class, 'daftarAkses']);
    Route::get('hapus-akses-user', [AkunController::class, 'hapusAksesUser']);
    Route::post('simpan-akses-user', [AkunController::class, 'simpanAksesUser']);

    Route::post('update-timbang-pabrik', [BerangkatPabrikController::class, 'updateTimbangPabrik']);
    Route::delete('timbang-pabrik-mobil/{id}', [BerangkatPabrikController::class, 'hapusTimbangPabrikMobil']);
    Route::delete('timbang-muatan/{id}', [BerangkatTimbangController::class, 'hapusTimbangMuatan']);
    // Route::delete('hapus-mobil-berangkat/{id}', [BerangkatTimbangController::class, 'hapusMobilBerangkat']);
});
