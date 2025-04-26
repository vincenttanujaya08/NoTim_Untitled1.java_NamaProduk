@extends('layouts.app3')
@section('title','Dashboard Buyer')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8">
    <h1 class="text-3xl font-semibold">Dashboard Buyer</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Total Pesanan --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">🛒</span>
            <div>
                <div class="text-sm text-gray-500">Total Pesanan</div>
                <div class="text-2xl font-bold">{{ $totalOrders }}</div>
            </div>
        </div>
        {{-- Pesanan Belum Lunas --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">⏳</span>
            <div>
                <div class="text-sm text-gray-500">Pesanan Belum Lunas</div>
                <div class="text-2xl font-bold">{{ $pendingOrders }}</div>
            </div>
        </div>
        {{-- Pesanan Terakhir --}}
        <div class="bg-white rounded-lg shadow p-5 flex items-center">
            <span class="text-4xl mr-4">📅</span>
            <div>
                <div class="text-sm text-gray-500">Pesanan Terakhir</div>
                <div class="text-2xl font-bold">
                    {{ optional($lastOrder)->order_date ?? '–' }}
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold">Aksi Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('buyer.katalog') }}"
            class="block bg-green-950 text-white rounded-lg shadow p-6 text-center hover:bg-green-800">
            📦<br><span class="mt-2 block font-medium">Lihat Katalog</span>
        </a>
        <a href="{{ route('buyer.orders.index') }}"
            class="block bg-green-950 text-white rounded-lg shadow p-6 text-center hover:bg-green-800">
            📜<br><span class="mt-2 block font-medium">Riwayat Pesanan</span>
        </a>
    </div>
</div>
@endsection