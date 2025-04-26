{{-- resources/views/super/cooperatives/create.blade.php --}}
@extends('layouts.app')

@section('title','Tambah Koperasi Baru')

@section('content')
<div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden mx-auto mt-12">
    <!-- Header -->
    <div class="bg-green-950 p-5">
        <h1 class="text-white text-2xl font-semibold">Tambah Koperasi Baru</h1>
    </div>

    <!-- Form Container -->
    <div class="p-6">
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error List -->
        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('cooperatives.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Koperasi
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Masukkan nama koperasi" />
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                    Alamat
                </label>
                <textarea
                    name="address"
                    id="address"
                    rows="4"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('super.dashboard') }}"
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-green-800 text-white rounded-lg hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection