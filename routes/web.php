<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/katalog', function () {
    return view('katalog');
});

//login
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware('auth')->group(function () {
    // Super Admin
    Route::get('super/dashboard', 'SuperController@dashboard')
        ->name('super.dashboard')
        ->middleware('role:Super Admin');
    // Admin Koperasi
    Route::get('koperasi/dashboard', 'KoperasiController@dashboard')
        ->name('koperasi.dashboard')
        ->middleware('role:Admin Koperasi');
    // Petugas Lapangan
    Route::get('field/dashboard', 'FieldController@dashboard')
        ->name('field.dashboard')
        ->middleware('role:Petugas Lapangan');
    // Petani
    Route::get('farmer/dashboard', 'FarmerController@dashboard')
        ->name('farmer.dashboard')
        ->middleware('role:Petani');
    // Pembeli
    Route::get('buyer/dashboard', 'BuyerController@dashboard')
        ->name('buyer.dashboard')
        ->middleware('role:Pembeli');
});
