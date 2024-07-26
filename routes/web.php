<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
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

//untuk operator
Route::get('/operator/timbang-tiket', [WebController::class, 'timbangTiket'])->name('timbang-tiket');
Route::get('/operator/timbang-berangkat', [WebController::class, 'timbangBerangkat'])->name('timbang-berangkat');
