<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Commodity;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Harvest;

class KoperasiController extends Controller
{
    /**
     * Ambil cooperative_id dari user yang sedang login.
     */
    protected function coopId(): int
    {
        return Auth::user()->cooperative_id;
    }

    /**
     * Dashboard: ringkasan petani, stok, pesanan, panen, omzet & piutang.
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (! $user || $user->role_id !== 2) {
            return redirect()->route('login');
        }
        $cid = $this->coopId();

        // 1) Total Petani pada koperasi ini
        $totalFarmers = Farmer::where('cooperative_id', $cid)->count();

        // 2) Total Stok = SUM(panen) – SUM(terjual)
        $harvestSum = Harvest::join('farmers', 'harvests.farmer_id', '=', 'farmers.id')
            ->where('farmers.cooperative_id', $cid)
            ->sum('harvests.quantity');

        $soldSum = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->sum('order_items.quantity');

        $totalStocks = $harvestSum - $soldSum;

        // 3) Pesanan pending untuk koperasi ini
        $pendingOrders = Order::join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->where('orders.payment_status', '!=', 'PAID')
            ->count();

        // 4) Total catatan panen (jumlah baris harvest)
        $totalHarvest = Harvest::join('farmers', 'harvests.farmer_id', '=', 'farmers.id')
            ->where('farmers.cooperative_id', $cid)
            ->count();

        // 5) Omzet (sum total_amount dari pesanan PAID)
        $omzet = Order::join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->where('orders.payment_status', 'PAID')
            ->sum('orders.total_amount');

        // 6) Piutang (sum total_amount dari pesanan selain PAID)
        $piutang = Order::join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->where('orders.payment_status', '!=', 'PAID')
            ->sum('orders.total_amount');

        return view('dashboards.koperasi', compact(
            'totalFarmers',
            'totalStocks',
            'pendingOrders',
            'totalHarvest',
            'omzet',
            'piutang'
        ));
    }

    // =================================
    // 1) CRUD PETANI
    // =================================

    public function farmersIndex()
    {
        $farmers = Farmer::with('user')
            ->where('cooperative_id', $this->coopId())
            ->paginate(10);

        return view('koperasi.farmers.index', compact('farmers'));
    }

    public function farmersCreate()
    {
        return view('koperasi.farmers.create');
    }

    public function farmersStore(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $cid = $this->coopId();

        DB::transaction(function () use ($data, $cid) {
            $user = User::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'password'       => bcrypt('password'),
                'phone'          => $data['phone']  ?? null,
                'address'        => $data['address'] ?? null,
                'cooperative_id' => $cid,
                'role_id'        => 4,  // Petani
            ]);
            Farmer::create([
                'user_id'        => $user->id,
                'cooperative_id' => $cid,
                'balance'        => 0,
                'join_date'      => now(),
            ]);
        });

        return redirect()
            ->route('koperasi.farmers.index')
            ->with('success', 'Petani berhasil ditambahkan.');
    }

    public function farmersEdit(Farmer $farmer)
    {
        return view('koperasi.farmers.edit', compact('farmer'));
    }

    public function farmersUpdate(Request $request, Farmer $farmer)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $farmer->user->update($data);

        return back()->with('success', 'Data petani diperbarui.');
    }

    public function farmersDestroy(Farmer $farmer)
    {
        $farmer->user->delete();
        return back()->with('success', 'Petani dihapus.');
    }

    // =================================
    // 2) STOK (hitung real-time dari harvest & order_items)
    // =================================

    public function stocksIndex()
    {
        $cid = $this->coopId();

        // Ambil total panen per komoditas+grade
        $harvests = DB::table('harvests')
            ->join('farmers', 'harvests.farmer_id', '=', 'farmers.id')
            ->where('farmers.cooperative_id', $cid)
            ->select('harvests.commodity_id', 'harvests.grade', DB::raw('SUM(harvests.quantity) as total_harvest'))
            ->groupBy('harvests.commodity_id', 'harvests.grade')
            ->get();

        // Ambil total terjual per komoditas+grade
        $sold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->select('order_items.commodity_id', 'order_items.grade', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.commodity_id', 'order_items.grade')
            ->get()
            ->keyBy(fn($i) => $i->commodity_id . '_' . $i->grade);

        // Hitung stok bersih
        $names  = Commodity::pluck('name', 'id');
        $stocks = $harvests->map(function ($h) use ($sold, $names) {
            $key = $h->commodity_id . '_' . $h->grade;
            return [
                'commodity' => $names[$h->commodity_id] ?? '–',
                'grade'     => $h->grade,
                'qty'       => $h->total_harvest
                    - ($sold->has($key) ? $sold[$key]->total_sold : 0),
            ];
        });

        return view('koperasi.stocks.index', compact('stocks'));
    }

    // =================================
    // 3) PESANAN MANAGEMENT
    // =================================

    public function ordersIndex()
    {
        $cid = $this->coopId();

        $orders = Order::join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $cid)
            ->select('orders.*')
            ->latest('orders.created_at')
            ->paginate(10);

        return view('koperasi.orders.index', compact('orders'));
    }

    public function ordersShow(Order $order)
    {
        $order->load(['items', 'buyer.user']);
        return view('koperasi.orders.show', compact('order'));
    }

    public function ordersAccept(Order $order)
    {
        $order->update(['status' => 'ACCEPTED']);
        return back()->with('success', 'Pesanan berhasil diterima.');
    }

    public function ordersReject(Request $request, Order $order)
    {
        $reason = $request->input('reason', '-');
        $order->update(['status' => 'REJECTED', 'reason' => $reason]);
        return back()->with('success', 'Pesanan berhasil ditolak.');
    }

    public function ordersSchedule(Request $request, Order $order)
    {
        $data = $request->validate([
            'pickup_date'  => 'required|date',
            'deliver_date' => 'required|date|after:pickup_date',
        ]);
        $order->update($data);
        return back()->with('success', 'Jadwal kirim tersimpan.');
    }

    // =================================
    // 4) LAPORAN
    // =================================

    public function reportHarvest()
    {
        $harvests = Harvest::join('farmers', 'harvests.farmer_id', '=', 'farmers.id')
            ->where('farmers.cooperative_id', $this->coopId())
            ->paginate(15);

        return view('koperasi.reports.harvest', compact('harvests'));
    }

    public function reportFinance()
    {
        $orders = Order::join('buyers', 'orders.buyer_id', '=', 'buyers.id')
            ->join('users', 'buyers.user_id', '=', 'users.id')
            ->where('users.cooperative_id', $this->coopId())
            ->paginate(15);

        return view('koperasi.reports.finance', compact('orders'));
    }
}
