{{-- resources/views/buyer/orders/index.blade.php --}}
@extends('layouts.app3')
@section('title','Riwayat Pesanan')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">

    {{-- Header --}}
    <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>

    {{-- Jika tidak ada order --}}
    @if($orders->isEmpty())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <p class="text-yellow-700">Anda belum memiliki riwayat pesanan.</p>
    </div>
    @endif

    {{-- Daftar order sebagai card --}}
    <div class="grid gap-4">
        @foreach($orders as $o)
        <div class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row sm:items-center justify-between">

            {{-- Info Pesanan --}}
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="text-gray-600"><svg class="w-5 h-5 inline text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-2 14h4a2 2 0 002-2v-5H4v5a2 2 0 002 2h4m4 0v2m-4-2v2" />
                        </svg>
                    </span>
                    <span class="text-sm text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($o->order_date)->isoFormat('D MMM YYYY') }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Total:</span>
                    <span class="text-lg font-semibold text-gray-800">
                        Rp {{ number_format($o->total_amount,0,',','.') }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Status:</span>
                    @if($o->payment_status === 'PAID')
                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                        {{ $o->payment_status }}
                    </span>
                    @elseif($o->payment_status === 'UNPAID' || $o->payment_status === 'PARTIAL')
                    <span class="inline-block px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                        {{ $o->payment_status }}
                    </span>
                    @else
                    <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                        {{ $o->payment_status }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Aksi --}}
            <div class="mt-4 sm:mt-0 flex space-x-2">
                {{-- Detail --}}
                <a href="{{ route('buyer.orders.show', $o->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                    Detail
                </a>

                {{-- Repeat --}}
                <form action="{{ route('buyer.orders.repeat', $o->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                        Repeat
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>


</div>
@endsection