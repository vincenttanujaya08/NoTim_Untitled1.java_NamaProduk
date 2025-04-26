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

// crud koperasi
Route::get('cooperatives/create', [SuperController::class, 'createCooperative'])
    ->name('cooperatives.create');
Route::post('cooperatives', [SuperController::class, 'storeCooperative'])
    ->name('cooperatives.store');

Route::delete('cooperatives/{coop}', [SuperController::class, 'destroyCooperative'])
    ->name('cooperatives.destroy');




// Petani CRUD
Route::get('/koperasi/farmers',            [KoperasiController::class, 'farmersIndex'])->name('koperasi.farmers.index');
Route::get('/koperasi/farmers/create',     [KoperasiController::class, 'farmersCreate'])->name('koperasi.farmers.create');
Route::post('/koperasi/farmers',            [KoperasiController::class, 'farmersStore'])->name('koperasi.farmers.store');
Route::get('/koperasi/farmers/{farmer}/edit', [KoperasiController::class, 'farmersEdit'])->name('koperasi.farmers.edit');
Route::put('/koperasi/farmers/{farmer}',    [KoperasiController::class, 'farmersUpdate'])->name('koperasi.farmers.update');
Route::delete('/koperasi/farmers/{farmer}',   [KoperasiController::class, 'farmersDestroy'])->name('koperasi.farmers.destroy');

// Stok CRUD + Bulk
Route::get('/koperasi/stocks',              [KoperasiController::class, 'stocksIndex'])->name('koperasi.stocks.index');
Route::get('/koperasi/stocks/create',       [KoperasiController::class, 'stocksCreate'])->name('koperasi.stocks.create');
Route::post('/koperasi/stocks',              [KoperasiController::class, 'stocksStore'])->name('koperasi.stocks.store');
Route::get('/koperasi/stocks/{stock}/edit', [KoperasiController::class, 'stocksEdit'])->name('koperasi.stocks.edit');
Route::put('/koperasi/stocks/{stock}',      [KoperasiController::class, 'stocksUpdate'])->name('koperasi.stocks.update');
Route::delete('/koperasi/stocks/{stock}',     [KoperasiController::class, 'stocksDestroy'])->name('koperasi.stocks.destroy');
Route::get('/koperasi/stocks/bulk',         [KoperasiController::class, 'stocksBulkForm'])->name('koperasi.stocks.bulk.form');
Route::post('/koperasi/stocks/bulk',          [KoperasiController::class, 'stocksProcessBulk'])->name('koperasi.stocks.bulk.process');

// Pesanan
Route::get('/koperasi/orders',               [KoperasiController::class, 'ordersIndex'])->name('koperasi.orders.index');
Route::get('/koperasi/orders/{order}',       [KoperasiController::class, 'ordersShow'])->name('koperasi.orders.show');
Route::post('/koperasi/orders/{order}/accept', [KoperasiController::class, 'ordersAccept'])->name('koperasi.orders.accept');
Route::post('/koperasi/orders/{order}/reject', [KoperasiController::class, 'ordersReject'])->name('koperasi.orders.reject');
Route::post('/koperasi/orders/{order}/schedule', [KoperasiController::class, 'ordersSchedule'])->name('koperasi.orders.schedule');

// Laporan
Route::get('/koperasi/reports/harvest', [KoperasiController::class, 'reportHarvest'])->name('koperasi.reports.harvest');
Route::get('/koperasi/reports/finance', [KoperasiController::class, 'reportFinance'])->name('koperasi.reports.finance');
