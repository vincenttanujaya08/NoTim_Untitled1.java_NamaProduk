@extends('layouts.app3')
@section('title','Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow space-y-6">
    {{-- Header Pesanan --}}
    <div class="space-y-2">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <span class="font-medium text-gray-600">Tanggal Pesanan:</span>
                <span>{{ \Carbon\Carbon::parse($order->order_date)->isoFormat('D MMMM YYYY') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Status Pembayaran:</span>
                <span
                    class="px-2 py-1 rounded 
            @if($order->payment_status=='PAID') bg-green-100 text-green-800 
            @elseif($order->payment_status=='PARTIAL') bg-yellow-100 text-yellow-800 
            @else bg-red-100 text-red-800 @endif">
                    {{ $order->payment_status }}
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Total Amount:</span>
                <span>Rp {{ number_format($order->total_amount,0,',','.') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-600">Due Date:</span>
                <span>
                    {{ $order->due_date
            ? \Carbon\Carbon::parse($order->due_date)->isoFormat('D MMMM YYYY')
            : '-' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Daftar Item --}}
    <div>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Item yang Dibeli</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Komoditas</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Grade</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Qty</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">Harga/Satuan</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-2 text-gray-700">{{ $item->commodity->name }}</td>
                        <td class="px-4 py-2 text-center text-gray-700">{{ $item->grade }}</td>
                        <td class="px-4 py-2 text-center text-gray-700">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 text-right text-gray-700">
                            Rp {{ number_format($item->price,0,',','.') }}
                        </td>
                        <td class="px-4 py-2 text-right text-gray-700">
                            Rp {{ number_format($item->total_price,0,',','.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat Pembayaran --}}
    @if($order->payments->isNotEmpty())
    <div>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Riwayat Pembayaran</h2>
        <ul class="space-y-2">
            @foreach($order->payments as $pay)
            <li class="flex justify-between bg-gray-50 p-3 rounded">
                <div>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($pay->payment_date)->isoFormat('D MMM YYYY') }}</span>
                    <span class="text-sm text-gray-600">({{ $pay->method }})</span>
                </div>
                <div class="font-semibold text-gray-800">
                    Rp {{ number_format($pay->amount,0,',','.') }}
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Aksi: Bayar & Kembali --}}
    <div class="flex justify-between items-center">
        <a href="{{ route('buyer.orders.index') }}"
            class="px-5 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
            ← Kembali
        </a>

        @if($order->payment_status !== 'PAID')
        <form action="{{ route('buyer.orders.pay',$order->id) }}" method="POST">
            @csrf
            <button
                type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Bayar Sekarang
            </button>
        </form>
        @endif
    </div>
</div>
@endsection