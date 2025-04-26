@extends('layouts.app2')
@section('title','Daftar Petani')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6 bg-white rounded-lg shadow">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Daftar Petani</h1>
        <a href="{{ route('koperasi.farmers.create') }}"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            + Tambah Petani
        </a>
    </div>

    @if(session('success'))
    <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Telepon</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($farmers as $farmer)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $farmer->user->name }}</td>
                <td class="px-4 py-2">{{ $farmer->user->email }}</td>
                <td class="px-4 py-2">{{ $farmer->user->phone }}</td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('koperasi.farmers.edit',$farmer->id) }}"
                        class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('koperasi.farmers.destroy',$farmer->id) }}"
                        method="POST" class="inline"
                        onsubmit="return confirm('Hapus petani?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">
                    Belum ada data petani.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $farmers->links() }}</div>
</div>
@endsection