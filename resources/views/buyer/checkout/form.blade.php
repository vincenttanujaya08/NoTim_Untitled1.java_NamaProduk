@extends('layouts.app3')
@section('title','Checkout')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4">Checkout</h1>

    <form action="{{ route('buyer.checkout.process') }}" method="POST">
        @csrf
        <table class="w-full mb-4 table-auto border-collapse">
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
                @php $grand=0; @endphp
                @foreach($items as $it)
                @php $sub = $it['qty'] * $it['price']; $grand += $sub; @endphp
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $it['commodity'] }}</td>
                    <td class="px-4 py-2">{{ $it['grade'] }}</td>
                    <td class="px-4 py-2">{{ $it['qty'] }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($it['price'],0,',','.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($sub,0,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Total:</td>
                    <td class="px-4 py-2 font-bold">Rp {{ number_format($grand,0,',','.') }}</td>
                </tr>
            </tbody>
        </table>

        <button type="submit"
            class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Checkout & Buat PO
        </button>
    </form>
</div>
@endsection