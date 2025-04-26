@extends('layouts.app2')
@section('title','Laporan Keuangan')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-semibold mb-4">Laporan Keuangan</h1>

    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">#Order</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Total Rp</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $o->id }}</td>
                <td class="px-4 py-2">{{ $o->order_date }}</td>
                <td class="px-4 py-2">Rp {{ number_format($o->total_amount,0,',','.') }}</td>
                <td class="px-4 py-2">{{ $o->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection