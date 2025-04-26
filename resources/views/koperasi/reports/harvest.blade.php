@extends('layouts.app2')
@section('title','Laporan Panen')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-semibold mb-4">Laporan Panen</h1>

    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Petani</th>
                <th class="px-4 py-2">Komoditas</th>
                <th class="px-4 py-2">Grade</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Total Rp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($harvests as $h)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $h->harvest_date }}</td>
                <td class="px-4 py-2">{{ $h->farmer->user->name }}</td>
                <td class="px-4 py-2">{{ $h->commodity->name }}</td>
                <td class="px-4 py-2">{{ $h->grade }}</td>
                <td class="px-4 py-2">{{ $h->quantity }}</td>
                <td class="px-4 py-2">Rp {{ number_format($h->total_amount,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $harvests->links() }}</div>
</div>
@endsection