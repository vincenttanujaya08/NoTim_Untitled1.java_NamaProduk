@extends('layouts.app2')

@section('title','Dashboard Koperasi')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8">

    <h1 class="text-3xl font-semibold text-gray-800">Dashboard Koperasi</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">👩‍🌾</span>
            <div>
                <div class="text-sm text-gray-500">Jumlah Petani</div>
                <div class="text-2xl font-bold">{{ $totalFarmers }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">📦</span>
            <div>
                <div class="text-sm text-gray-500">Total Stok</div>
                <div class="text-2xl font-bold">{{ $totalStocks }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">⏳</span>
            <div>
                <div class="text-sm text-gray-500">Pesanan Pending</div>
                <div class="text-2xl font-bold">{{ $pendingOrders }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">🍃</span>
            <div>
                <div class="text-sm text-gray-500">Catatan Panen</div>
                <div class="text-2xl font-bold">{{ $totalHarvest }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">💰</span>
            <div>
                <div class="text-sm text-gray-500">Total Omzet</div>
                <div class="text-2xl font-bold">Rp {{ number_format($omzet,0,',','.') }}</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">📋</span>
            <div>
                <div class="text-sm text-gray-500">Total Piutang</div>
                <div class="text-2xl font-bold">Rp {{ number_format($piutang,0,',','.') }}</div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold text-gray-700">Navigasi Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('koperasi.farmers.index') }}"
            class="block bg-blue-600 text-white rounded-lg shadow p-6 text-center hover:bg-blue-700 transition">
            👥<br><span class="mt-2 block font-medium">Kelola Petani</span>
        </a>
        <a href="{{ route('koperasi.stocks.index') }}"
            class="block bg-indigo-600 text-white rounded-lg shadow p-6 text-center hover:bg-indigo-700 transition">
            📊<br><span class="mt-2 block font-medium">Kelola Stok</span>
        </a>
        <a href="{{ route('koperasi.orders.index') }}"
            class="block bg-yellow-600 text-white rounded-lg shadow p-6 text-center hover:bg-yellow-700 transition">
            📝<br><span class="mt-2 block font-medium">Kelola Pesanan</span>
        </a>
        <a href="{{ route('koperasi.reports.harvest') }}"
            class="block bg-green-600 text-white rounded-lg shadow p-6 text-center hover:bg-green-700 transition">
            📅<br><span class="mt-2 block font-medium">Laporan Panen</span>
        </a>
        <a href="{{ route('koperasi.reports.finance') }}"
            class="block bg-pink-600 text-white rounded-lg shadow p-6 text-center hover:bg-pink-700 transition">
            📈<br><span class="mt-2 block font-medium">Laporan Keuangan</span>
        </a>
    </div>

</div>
@endsection