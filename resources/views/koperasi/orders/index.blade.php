@extends('layouts.app2')
@section('title','Daftar Pesanan')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">

    <h1 class="text-2xl font-semibold mb-4">Daftar Pesanan</h1>

    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">#Order</th>
                <th class="px-4 py-2">Pembeli</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $order->id }}</td>
                <td class="px-4 py-2">{{ $order->buyer->user->name }}</td>
                <td class="px-4 py-2">{{ $order->order_date }}</td>
                <td class="px-4 py-2">Rp {{ number_format($order->total_amount,0,',','.') }}</td>
                <td class="px-4 py-2">{{ $order->status }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('koperasi.orders.show',$order->id) }}"
                        class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 text-center text-gray-500">Belum ada pesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection