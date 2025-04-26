{{-- resources/views/super-dashboard.blade.php --}}
@extends('layouts.app')

@section('title','Dashboard Super Admin')

@section('content')
{{-- Header --}}
<h1 class="text-3xl font-semibold text-gray-800 mb-6">Dashboard Super Admin</h1>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @php
    $cards = [
    ['title'=>'Total Koperasi','value'=>$totalCoops,'icon'=>'👥'],
    ['title'=>'Total Users','value'=>$totalUsers,'icon'=>'🧑‍🤝‍🧑'],
    ['title'=>'Petani','value'=>$totalFarmers,'icon'=>'🌾'],
    ['title'=>'Pembeli','value'=>$totalBuyers,'icon'=>'🛒'],
    ['title'=>'Pesanan','value'=>$totalOrders,'icon'=>'📦'],
    ['title'=>'Catatan Panen','value'=>$totalHarvests,'icon'=>'📈'],
    ];
    @endphp

    @foreach($cards as $c)
    <div class="bg-white rounded-lg shadow p-5 flex items-center space-x-4">
        <div class="text-4xl">{{ $c['icon'] }}</div>
        <div>
            <div class="text-sm text-gray-500">{{ $c['title'] }}</div>
            <div class="text-2xl font-bold text-gray-800">{{ $c['value'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Financial Numbers --}}
<div class="bg-white rounded-lg shadow p-5 mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Angka Finansial</h2>
    <div class="flex gap-4">
        <div class="flex-1 bg-green-100 rounded-lg p-4">
            <div class="text-sm text-green-600">Total Pendapatan</div>
            <div class="text-2xl font-bold text-green-800">Rp {{ number_format($omzet,0,',','.') }}</div>
        </div>
        <div class="flex-1 bg-red-100 rounded-lg p-4">
            <div class="text-sm text-red-600">Total Piutang</div>
            <div class="text-2xl font-bold text-red-800">Rp {{ number_format($piutang,0,',','.') }}</div>
        </div>
    </div>
</div>

{{-- Search Komoditas --}}
<div class="bg-white rounded-lg shadow p-5 mb-8">
    <input
        id="commoditySearch" type="text"
        placeholder="🔍 Cari komoditas..."
        class="w-full sm:w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" />
</div>

{{-- Stok Global per Komoditas --}}
<div class="bg-white rounded-lg shadow p-5 mb-8 overflow-x-auto">
    <div id="commodityContainer" class="flex space-x-4">
        @foreach($stokByCommodity as $item)
        <div
            class="commodity-card min-w-[280px] bg-gray-50 rounded-lg shadow-inner p-4"
            data-name="{{ strtolower($item['commodity']) }}">
            <h3 class="font-semibold text-gray-800 mb-2">{{ $item['commodity'] }}</h3>
            <div class="space-y-1">
                @forelse($item['records'] as $rec)
                <div class="flex justify-between text-sm text-gray-700">
                    <span>{{ $rec['coop_name'] }} ({{ $rec['grade'] }})</span>
                    <span>{{ $rec['qty'] }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada stok.</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Daftar Koperasi --}}
<div class="bg-white rounded-lg shadow p-5 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">Kelola Koperasi</h2>
        <a href="{{ route('cooperatives.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Tambah Koperasi
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Petani</th>
                    <th class="px-4 py-2">Users</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cooperatives as $coop)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $coop->name }}</td>
                    <td class="px-4 py-2">{{ $coop->farmers_count }}</td>
                    <td class="px-4 py-2">{{ $coop->users_count }}</td>
                    <td class="px-4 py-2">
                        <form
                            action="{{ route('cooperatives.destroy', $coop->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin hapus?')"
                            class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Logout --}}
<div class="text-center">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button
            type="submit"
            class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-900">
            Logout
        </button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Search Komoditas
        document.getElementById('commoditySearch')
            .addEventListener('input', function() {
                const q = this.value.trim().toLowerCase();
                document.querySelectorAll('.commodity-card').forEach(card => {
                    const name = card.dataset.name;
                    card.classList.toggle('hidden', q && !name.includes(q));
                });
            });
    });
</script>
@endpush

@endsection