@extends('layouts.app3')
@section('title','Katalog Stok')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4" x-data="catalogComponent()" x-init="init()">

    {{-- Search Bar --}}
    <div class="mb-8 flex justify-center">
        <input
            type="text"
            x-model="searchTerm"
            placeholder="🔍 Cari komoditas..."
            class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
    </div>

    {{-- Grid Komoditas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="item in filteredCommodities" :key="item.commodity_id">
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                {{-- Header --}}
                <div class="bg-indigo-600 px-5 py-4">
                    <h2 class="text-xl font-semibold text-white text-center" x-text="item.commodity"></h2>
                </div>

                {{-- Pilih Grade & Seller --}}
                <div class="p-5 space-y-4 flex-grow">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                        <select
                            x-model="selectedGrade[item.commodity_id]"
                            class="w-full border-gray-300 rounded-md focus:ring-indigo-300">
                            <option value="">Pilih Grade</option>
                            <template x-for="rec in item.records" :key="`${item.commodity_id}_${rec.grade}`">
                                <option :value="rec.grade" x-text="`Grade ${rec.grade}`"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seller</label>
                        <select
                            x-model="selectedSeller[item.commodity_id]"
                            class="w-full border-gray-300 rounded-md focus:ring-indigo-300">
                            <option value="">Pilih Seller</option>
                            <template
                                x-for="s in sellersByCommodity(item.commodity_id)"
                                :key="s.coop_id">
                                <option :value="s.coop_id" x-text="s.coop_name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input
                            type="number"
                            x-model.number="quantity[item.commodity_id]"
                            min="0.01" step="0.01"
                            placeholder="0.00"
                            class="w-full border-gray-300 rounded-md px-2 py-1 focus:ring-indigo-300" />
                    </div>
                </div>

                {{-- Footer: Tambah ke Keranjang --}}
                <div class="bg-gray-50 px-5 py-4 text-center">
                    <button
                        @click="addToCart(item.commodity_id)"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9" />
                        </svg>
                        Tambah Keranjang
                    </button>
                </div>
            </div>
        </template>

        <template x-if="filteredCommodities.length === 0">
            <div class="col-span-3 text-center text-gray-500 py-10">
                Tidak ada komoditas ditemukan.
            </div>
        </template>
    </div>

    {{-- Keranjang Front-end --}}
    <div class="mt-12 max-w-4xl mx-auto bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-indigo-800 px-6 py-4">
            <h3 class="text-white font-semibold">🛒 Keranjang Anda</h3>
        </div>
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm text-gray-600">Komoditas</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-600">Grade</th>
                    <th class="px-4 py-2 text-center text-sm text-gray-600">Seller</th>
                    <th class="px-4 py-2 text-right text-sm text-gray-600">Qty</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <template x-for="(it, idx) in cart" :key="idx">
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700" x-text="it.commodity"></td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700" x-text="it.grade"></td>
                        <td class="px-4 py-2 text-center text-sm text-gray-700" x-text="it.coop_name"></td>
                        <td class="px-4 py-2 text-right text-sm text-gray-700" x-text="it.qty.toFixed(2)"></td>
                        <td class="px-4 py-2 text-right">
                            <button @click="removeFromCart(idx)"
                                class="text-red-600 hover:underline text-sm">Hapus</button>
                        </td>
                    </tr>
                </template>
                <tr x-show="cart.length===0">
                    <td colspan="5" class="py-4 text-center text-gray-500">Keranjang kosong.</td>
                </tr>
            </tbody>
        </table>
        {{-- Checkout Button --}}
        <div class="p-6 bg-gray-50 text-right">
            <button
                @click="checkout()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h18v4H3V3zm0 8h18v10H3V11zM7 15h5v2H7v-2z" />
                </svg>
                Checkout
            </button>
        </div>

    </div>

</div>

<script>
    function catalogComponent() {
        return {
            searchTerm: '',
            cart: [],

            stocks: @json($stocksByCommodity),

            selectedGrade: {},
            selectedSeller: {},
            quantity: {},

            init() {
                this.stocks.forEach(c => {
                    this.selectedGrade[c.commodity_id] = ''
                    this.selectedSeller[c.commodity_id] = ''
                    this.quantity[c.commodity_id] = null
                })
            },

            get filteredCommodities() {
                if (!this.searchTerm) return this.stocks;
                return this.stocks.filter(c =>
                    c.commodity.toLowerCase()
                    .includes(this.searchTerm.toLowerCase())
                );
            },

            sellersByCommodity(cid) {
                const recs = this.stocks.find(c => c.commodity_id === cid).records;
                const allS = recs.flatMap(r => r.sellers);
                return Array.from(
                    new Map(allS.map(s => [s.coop_id, s])).values()
                );
            },

            addToCart(cid) {
                const grade = this.selectedGrade[cid];
                const coop = this.selectedSeller[cid];
                const qty = this.quantity[cid];
                if (!grade || !coop || !qty) return;

                const comm = this.stocks.find(c => c.commodity_id === cid).commodity;
                const seller = this.sellersByCommodity(cid).find(s => s.coop_id == coop);

                this.cart.push({
                    commodity_id: cid,
                    commodity: comm,
                    grade,
                    coop_id: seller.coop_id,
                    coop_name: seller.coop_name,
                    qty: parseFloat(qty),
                });

                // reset
                this.selectedGrade[cid] = '';
                this.selectedSeller[cid] = '';
                this.quantity[cid] = null;
            },

            removeFromCart(i) {
                this.cart.splice(i, 1);
            },

            checkout() {
                if (this.cart.length === 0) {
                    alert('Keranjang kosong, tambahkan barang dulu 😊');
                    return;
                }
                let summary = this.cart
                    .map(it => `${it.commodity} (Grade ${it.grade}) x${it.qty}`)
                    .join('\n');
                if (confirm(`Anda akan checkout:\n\n${summary}\n\nLanjutkan?`)) {
                    this.cart = [];
                    alert('Checkout berhasil! Keranjang telah dikosongkan.');
                }
            }
        }
    }
</script>
@endsection