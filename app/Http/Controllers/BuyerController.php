<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CommodityStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Harvest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\OrderPayment;

class BuyerController extends Controller
{
    /** Helper: cart disimpan di session */
    protected function cart()
    {
        return session()->get('cart', []);
    }

    protected function saveCart($cart)
    {
        session()->put('cart', $cart);
    }

    /** Dashboard Buyer */
    public function dashboard()
    {
        $user = Auth::user();
        // total orders & outstanding
        $totalOrders   = Order::where('buyer_id', $user->buyer->id)->count();
        $pendingOrders = Order::where('buyer_id', $user->buyer->id)
            ->where('payment_status', '!=', 'PAID')
            ->count();
        $lastOrder     = Order::where('buyer_id', $user->buyer->id)
            ->latest('order_date')->first();

        return view('dashboards.buyer', compact(
            'totalOrders',
            'pendingOrders',
            'lastOrder'
        ));
    }

    /** 2. Katalog Stok: join commodity_stocks + commodities */
    public function katalog()
    {
        // 1) Ambil stok global
        $rawStocks = CommodityStock::with('commodity')
            ->select('commodity_id', 'grade', 'quantity')
            ->orderBy('commodity_id')
            ->get();

        // 2) Ambil seller (koperasi) per commodity+grade
        $rawSellers = DB::table('harvests as h')
            ->join('farmers as f', 'h.farmer_id', 'f.id')
            ->join('cooperatives as c', 'f.cooperative_id', 'c.id')
            ->select(
                'h.commodity_id',
                'h.grade',
                'c.id as coop_id',
                'c.name as coop_name',
                'c.address'
            )
            ->distinct()
            ->get();

        // 3) Build lookup sellerMap[commodity_grade] = [ …sellers ]
        $sellerMap = [];
        foreach ($rawSellers as $r) {
            $key = "{$r->commodity_id}_{$r->grade}";
            $sellerMap[$key][] = [
                'coop_id'   => $r->coop_id,
                'coop_name' => $r->coop_name,
                'address'   => $r->address,
            ];
        }

        // 4) Group stok per commodity
        $stocksByCommodity = [];
        foreach ($rawStocks->groupBy('commodity_id') as $cid => $group) {
            $name = $group->first()->commodity->name;
            $records = [];
            foreach ($group as $s) {
                $k = "{$s->commodity_id}_{$s->grade}";
                $records[] = [
                    'grade'    => $s->grade,
                    'quantity' => $s->quantity,
                    'sellers'  => $sellerMap[$k] ?? [],
                ];
            }
            $stocksByCommodity[] = [
                'commodity_id' => $cid,
                'commodity'    => $name,
                'records'      => $records,
            ];
        }

        return view('buyer.katalog.index', compact('stocksByCommodity'));
    }


    /** 3a. Add to Cart */
    public function addToCart(Request $req)
    {
        $data = $req->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'grade'        => 'required|in:A,B,C',
            'qty'          => 'required|numeric|min:0.01',
        ]);

        $key = $data['commodity_id'] . '_' . $data['grade'];
        $cart = $this->cart();

        // jika sudah ada, tambah qty
        $cart[$key] = [
            'commodity_id' => $data['commodity_id'],
            'grade'        => $data['grade'],
            'qty'          => ($cart[$key]['qty'] ?? 0) + $data['qty']
        ];

        $this->saveCart($cart);
        return back()->with('success', 'Berhasil ditambahkan ke cart.');
    }





    /** 3b. Checkout Form */
    public function checkoutForm()
    {
        $cart = $this->cart();
        if (empty($cart)) {
            return redirect()->route('buyer.katalog')
                ->with('error', 'Cart kosong.');
        }
        // lookup detail commodity names & harga terakhir via harvest
        $items = [];
        foreach ($cart as $key => $line) {
            $comm = CommodityStock::with('commodity')
                ->where('commodity_id', $line['commodity_id'])
                ->where('grade', $line['grade'])
                ->first();
            $items[] = [
                'commodity_id' => $line['commodity_id'],
                'commodity'    => $comm->commodity->name,
                'grade'        => $line['grade'],
                'qty'          => $line['qty'],
                'price'        => Harvest::where('commodity_id', $line['commodity_id'])
                    ->where('grade', $line['grade'])
                    ->latest('harvest_date')
                    ->value('unit_price'),
            ];
        }

        return view('buyer.checkout.form', compact('items'));
    }

    /** 3c. Process Checkout: Insert Order + OrderItems */
    public function processCheckout(Request $req)
    {
        $cart  = $this->cart();
        if (empty($cart)) {
            return back()->with('error', 'Cart kosong.');
        }

        $buyerId = Auth::user()->buyer->id;
        $now      = Carbon::now()->toDateString();

        DB::transaction(function () use ($cart, $buyerId, $now) {
            // total amount
            $total = 0;
            $orderId = Order::create([
                'buyer_id'      => $buyerId,
                'order_date'    => $now,
                'total_amount'  => 0,              // update nanti
                'payment_status' => 'UNPAID',
                'due_date'      => null,
            ])->id;

            foreach ($cart as $line) {
                $price = Harvest::where('commodity_id', $line['commodity_id'])
                    ->where('grade', $line['grade'])
                    ->latest('harvest_date')
                    ->value('unit_price');
                $subtotal = $price * $line['qty'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id'     => $orderId,
                    'commodity_id' => $line['commodity_id'],
                    'grade'        => $line['grade'],
                    'quantity'     => $line['qty'],
                    'price'        => $price,
                    'total_price'  => $subtotal,
                ]);
            }

            // update total_amount
            Order::where('id', $orderId)->update(['total_amount' => $total]);
        });

        // clear cart
        session()->forget('cart');

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Order berhasil dibuat.');
    }

    /** 4a. Riwayat Pesanan */
    public function ordersIndex()
    {
        $buyerId = Auth::user()->buyer->id;
        $orders  = Order::where('buyer_id', $buyerId)
            ->latest('order_date')->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function ordersShow(Order $order)
    {


        // Eager load: items → commodity, dan payments
        $order->load('items.commodity', 'payments');

        return view('buyer.orders.show', compact('order'));
    }


    /** 5. Repeat Order: copy kembali ke cart */
    public function repeatOrder(Order $order)
    {
        $cart = $this->cart();

        foreach ($order->items as $it) {
            $key = $it->commodity_id . '_' . $it->grade;
            $cart[$key] = [
                'commodity_id' => $it->commodity_id,
                'grade'       => $it->grade,
                'qty'         => ($cart[$key]['qty'] ?? 0) + $it->quantity,
            ];
        }

        $this->saveCart($cart);
        return redirect()->route('buyer.checkout.form')
            ->with('success', 'Item lama ditambahkan ke cart.');
    }

    public function payOrder(Request $request, Order $order)
    {

        // Jika sudah lunas, block
        if ($order->payment_status === 'PAID') {
            return back()->with('info', 'Pesanan sudah lunas.');
        }

        // Buat payment record
        OrderPayment::create([
            'order_id'     => $order->id,
            'payment_date' => now()->toDateString(),
            'amount'       => $order->total_amount,
            'method'       => 'Manual',   // atau ambil dari $request
            'note'         => 'Pembayaran via tombol bayar',
        ]);

        // Update order status dan due_date (jika ada)
        $order->update([
            'payment_status' => 'PAID',
            'due_date'       => null,
        ]);

        return back()->with('success', 'Pembayaran berhasil. Status pesanan: PAID.');
    }
}
