<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CetakController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//default
Route::get('/', [WebController::class, 'auth']);
Route::get('/login', [WebController::class, 'auth']);

//untuk akun
Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');

Route::get('/akun', [WebController::class, 'akun'])->name('akun');
Route::get('/ram', [WebController::class, 'ram'])->name('ram');

//untuk operator
Route::get('/operator/timbang-tiket', [WebController::class, 'timbangTiket'])->name('timbang-tiket');
Route::get('/operator/timbang-berangkat', [WebController::class, 'timbangBerangkat'])->name('timbang-berangkat');


//untuk akun
Route::get('/cetak-timbang-pabrik', [CetakController::class, 'cetakTimbangPabrik'])->name('cetak-timbang-pabrik');
