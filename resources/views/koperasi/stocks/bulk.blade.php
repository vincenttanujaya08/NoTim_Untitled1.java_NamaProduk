@extends('layouts.app2')
@section('title','Bulk Upload Stok')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow space-y-6">

    <h1 class="text-2xl font-semibold">Bulk Upload Stok</h1>

    @if(session('success'))
    <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="p-3 bg-red-100 text-red-800 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('koperasi.stocks.bulk.process') }}"
        method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1">Pilih File CSV/XLSX</label>
            <input type="file" name="file"
                class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
        </div>
    </form>
</div>
@endsection