@extends('layouts.app2')
@section('title','Tambah Stok')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow space-y-6">

    <h1 class="text-2xl font-semibold">Tambah Stok</h1>

    @if($errors->any())
    <div class="p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('koperasi.stocks.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Komoditas</label>
            <select name="commodity_id" class="w-full border rounded px-3 py-2">
                @foreach($commodities as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Grade</label>
            <select name="grade" class="w-full border rounded px-3 py-2">
                <option>A</option>
                <option>B</option>
                <option>C</option>
            </select>
        </div>
        <div>
            <label class="block mb-1">Jumlah</label>
            <input name="quantity" type="number" min="0"
                class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('koperasi.stocks.index') }}"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
            <button type="submit"
                class="px-4 py-2 bg-green-800 text-white rounded hover:bg-green-700">Simpan</button>
        </div>
    </form>
</div>
@endsection