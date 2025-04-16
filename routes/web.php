<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/',[UserController::class, 'index']);          // menampilkan halaman awal user
    Route::post('/list',[UserController::class, 'list']);      // menampilkan data user dalam bentuk json datables
    Route::get('/create', [UserController::class, 'create']);  // menampilkan halaman form tambah user 
    Route::post('/',[UserController::class, 'store']);         // menyimpan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);            // Menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);                   // Menyimpan data user baru Ajax
    Route::get('/{id}',[UserController::class, 'show']);       // menampilkan detail user
    Route::get('/{id}/edit',[UserController::class, 'edit']);  // menampilkan halaman form edit user
    Route::put('/{id}',[UserController::class, 'update']);     // menyimpan perubahan data user
    // Ajax Edit
    Route::get('/{id}/edit_ajax',[UserController::class, 'edit_ajax']);  // menampilkan halaman form edit user
    Route::put('/{id}/update_ajax',[UserController::class, 'update_ajax']);     // menyimpan perubahan data user
    // Menghapus data user
    Route::delete('/{id}',[UserController::class, 'destroy']);  
    // Ajax Delete
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});  