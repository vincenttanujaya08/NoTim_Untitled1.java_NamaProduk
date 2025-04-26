@extends('layouts.app2')
@section('title','Edit Stok')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow space-y-6">

    <h1 class="text-2xl font-semibold">Edit Stok</h1>

    @if($errors->any())
    <div class="p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('koperasi.stocks.update',$stock->id) }}"
        method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block mb-1">Komoditas</label>
            <select disabled class="w-full border rounded px-3 py-2 bg-gray-100">
                <option>{{ $stock->commodity->name }}</option>
            </select>
        </div>
        <div>
            <label class="block mb-1">Grade</label>
            <input value="{{ $stock->grade }}" disabled
                class="w-full border rounded px-3 py-2 bg-gray-100" />
        </div>
        <div>
            <label class="block mb-1">Jumlah</label>
            <input name="quantity" type="number" min="0"
                value="{{ old('quantity',$stock->quantity) }}"
                class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end space-x-2">
            <a href="{{ route('koperasi.stocks.index') }}"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection