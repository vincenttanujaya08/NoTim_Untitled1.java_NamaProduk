@extends('layouts.app2')
@section('title','Tambah Petani')

@section('content')
<div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow space-y-6">

    <h1 class="text-2xl font-semibold">Tambah Petani</h1>

    @if($errors->any())
    <div class="p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('koperasi.farmers.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Nama</label>
            <input name="name" value="{{ old('name') }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300" />
        </div>
        <div>
            <label class="block mb-1">Email</label>
            <input name="email" type="email" value="{{ old('email') }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300" />
        </div>
        <div>
            <label class="block mb-1">Telepon</label>
            <input name="phone" value="{{ old('phone') }}"
                class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300" />
        </div>
        <div>
            <label class="block mb-1">Alamat</label>
            <textarea name="address"
                class="w-full border rounded px-3 py-2 focus:ring focus:border-blue-300">{{ old('address') }}</textarea>
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('koperasi.farmers.index') }}"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection