@extends('layouts.app2')
@section('title','Detail Pesanan')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow space-y-6">

    <h1 class="text-2xl font-semibold">Pesanan #{{ $order->id }}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <p><strong>Pembeli:</strong> {{ $order->buyer->user->name }}</p>
            <p><strong>Tgl Order:</strong> {{ $order->order_date }}</p>
        </div>
        <div>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_amount,0,',','.') }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
        </div>
    </div>

    <h2 class="text-xl font-semibold">Items</h2>
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Komoditas</th>
                <th class="px-4 py-2">Grade</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Harga</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $item->commodity->name }}</td>
                <td class="px-4 py-2">{{ $item->grade }}</td>
                <td class="px-4 py-2">{{ $item->quantity }}</td>
                <td class="px-4 py-2">Rp {{ number_format($item->price,0,',','.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($item->total_price,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Aksi Terima, Tolak, Jadwal --}}
    <div class="space-y-4">

        @if($order->status==='PENDING')
        <form action="{{ route('koperasi.orders.accept',$order->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Terima</button>
        </form>

        <form action="{{ route('koperasi.orders.reject',$order->id) }}" method="POST" class="inline">
            @csrf
            <input name="reason" placeholder="Alasan tolak" class="border rounded px-2 py-1" />
            <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tolak</button>
        </form>
        @endif

        <form action="{{ route('koperasi.orders.schedule',$order->id) }}"
            method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            @csrf
            <input type="date" name="pickup_date" class="border rounded px-2 py-1" />
            <input type="date" name="deliver_date" class="border rounded px-2 py-1" />
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Atur Jadwal</button>
        </form>

    </div>
</div>
@endsection