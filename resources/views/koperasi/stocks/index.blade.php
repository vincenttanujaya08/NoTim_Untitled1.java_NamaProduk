{{-- resources/views/koperasi/stocks/index.blade.php --}}
@extends('layouts.app2')
@section('title','Daftar Stok')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Daftar Stok</h1>
        <div class="space-x-2">
            <a href=""
                class="px-4 py-2 bg-green-800 text-white rounded hover:bg-green-700">
                Tambah
            </a>
            <a href=""
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-700">
                Bulk Upload
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-3 mb-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Komoditas</th>
                <th class="px-4 py-2">Grade</th>
                <th class="px-4 py-2">Jumlah</th>
                {{-- Jika memang butuh aksi CRUD stok langsung, tambahkan kolom Aksi --}}
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $stock)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $stock['commodity'] }}</td>
                <td class="px-4 py-2">{{ $stock['grade'] }}</td>
                <td class="px-4 py-2">{{ $stock['qty'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-4 text-center text-gray-500">
                    Belum ada stok.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination jika perlu --}}
    @if(method_exists($stocks, 'links'))
    <div class="mt-4">{{ $stocks->links() }}</div>
    @endif
</div>
@endsection