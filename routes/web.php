<?php

use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//Level Page
Route::get('/level', [LevelController::class, 'index']); 

//Kategori Page
Route::get('/kategori', [KategoriController::class, 'index']); 

// User Page
Route::get('/user', [UserController::class, 'index'])->name('/user');

// User Tambah
Route::get('/user/tambah',[UserController::class, 'tambah'])->name('/user/tambah');

// User Ubah
Route::get('/user/ubah/{id}',[UserController::class, 'ubah'])->name('/user/ubah');

// User Hapus
Route::get('/user/hapus/{id}',[UserController::class, 'hapus'])->name('/user/hapus');

// User Simpan
Route::post('/user/tambah_simpan',[UserController::class, 'tambah_simpan'])->name('/user/tambah_simpan');

// User Tambah Simpan
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('/user/ubah_simpan');

//Welcome Controller
Route::get('/', [WelcomeController::class, 'index']); 
