@extends('layouts.app3')
@section('title','Keranjang Saya')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow space-y-6">
    <h1 class="text-2xl font-semibold">Keranjang Saya</h1>

    @if(session('error'))
    <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Komoditas</th>
                <th class="px-4 py-2">Grade</th>
                <th class="px-4 py-2">Qty</th>
                <th class="px-4 py-2">Harga Satuan</th>
                <th class="px-4 py-2">Subtotal</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $it['commodity'] }}</td>
                <td class="px-4 py-2">{{ $it['grade'] }}</td>
                <td class="px-4 py-2">{{ $it['qty'] }}</td>
                <td class="px-4 py-2">Rp {{ number_format($it['unit_price'],0,',','.') }}</td>
                <td class="px-4 py-2">Rp {{ number_format($it['subtotal'],0,',','.') }}</td>
                <td class="px-4 py-2">
                    <form action="{{ route('buyer.cart.remove', $it['key']) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-between items-center mt-6">
        <div class="text-xl font-semibold">
            Total: Rp {{ number_format($total,0,',','.') }}
        </div>
        <div class="space-x-2">
            <a href="{{ route('buyer.katalog') }}"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Lanjut Belanja
            </a>
            <a href="{{ route('buyer.checkout.form') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Checkout
            </a>
        </div>
    </div>
</div>
@endsection