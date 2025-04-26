<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\KoperasiController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\BuyerController;

// Tampilkan form login
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// Proses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard per role (cek di controller)
Route::get('/super/dashboard', [SuperController::class, 'dashboard'])->name('super.dashboard');
Route::get('/koperasi/dashboard', [KoperasiController::class, 'dashboard'])->name('koperasi.dashboard');
Route::get('/field/dashboard', [FieldController::class, 'dashboard'])->name('field.dashboard');
Route::get('/farmer/dashboard', [FarmerController::class, 'dashboard'])->name('farmer.dashboard');
Route::get('/buyer/dashboard', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');
