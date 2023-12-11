<?php

use App\Http\Controllers\admincontroller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataPasien;
use App\Http\Controllers\UserControlController;
use App\Http\Controllers\UserControllController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->group(function(){
    Route::view('/','halaman_depan/index');
    Route::get('/sesi',[AuthController::class,'index'])->name('auth');
    Route::post('/sesi',[AuthController::class,'login']);
    Route::get('/reg',[AuthController::class,'create'])->name('registrasi');
    Route::post('/reg',[AuthController::class,'register']);
    Route::get('/verify/{verify_key}',[AuthController::class, 'verify']);
});

Route::middleware(['auth'])->group(function(){
    Route::redirect('/home','/user');
    Route::get('/admin',[AdminController::class,'index'])->name('admin')->middleware('userAkses:admin');
    Route::get('/user',[UserController::class,'index'])->name('user')->middleware('userAkses:user');

    Route::get('/datapasien',[DataPasien::class,'index'])->name('datapasien');
    Route::get('/dapatambah',[DataPasien::class,'tambah']);
    Route::get('/dapaedit/{id}',[DataPasien::class,'edit']);
    Route::post('/dapahapus/{id}',[DataPasien::class,'hapus']);

    Route::get('/usercontrol',[UserControlController::class,'index'])->name('usercontrol');

    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

        // new
    Route::post('/tambahdapa', [DataPasien::class, 'create']);
    Route::post('/editdapa', [DataPasien::class, 'change']);
    
    Route::get('/tambahuc', [UserControlController::class, 'tambah']);
    Route::get('/edituc/{id}', [UserControlController::class, 'edit']);
    Route::post('/hapusuc/{id}', [UserControlController::class, 'hapus']);
    Route::post('/tambahuc', [UserControlController::class, 'create']);
    Route::post('/edituc', [UserControlController::class, 'change']);
    
    Route::post('/uprole/{id}', [UproleController::class, 'index']);
});

