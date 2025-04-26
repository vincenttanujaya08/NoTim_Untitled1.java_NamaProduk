<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cooperative;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Buyer;
use App\Models\CommodityStock;
use App\Models\Order;
use App\Models\Harvest;
use Illuminate\Support\Facades\DB;
use App\Models\Commodity;

class SuperController extends Controller
{
    public function destroyCooperative(Cooperative $coop)
    {
        // optional: cek dependensi (petani/order) dulu jika perlu
        $coop->delete();

        return redirect()->route('super.dashboard')
            ->with('success', 'Koperasi berhasil dihapus.');
    }

    public function createCooperative()
    {
        return view('koperasi.createcoop');
    }

    public function storeCooperative(Request $request)
    {
        // validasi
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:cooperatives,name'],
            'address' => ['required', 'string'],
        ]);

        Cooperative::create($data);

        return redirect()->route('super.dashboard')
            ->with('success', 'Koperasi berhasil ditambahkan.');
    }



    public function dashboard()
    {
        // Ambil data agregat
        $totalCoops     = Cooperative::count();
        $totalUsers     = User::count() - 1;
        $totalFarmers   = Farmer::count();
        $totalBuyers    = Buyer::count();
        $totalOrders    = Order::count();
        $totalHarvests  = Harvest::count();

        // Omzet & piutang
        $omzet   = Order::sum('total_amount');
        $piutang = Order::where('payment_status', '!=', 'PAID')->sum('total_amount');

        // Stok global per komoditas-grade
        $stokGlobal = CommodityStock::with('commodity')
            ->selectRaw('commodity_id, grade, SUM(quantity) as qty')
            ->groupBy('commodity_id', 'grade')
            ->get();

        // Daftar koperasi beserta jumlah petani & user
        $cooperatives = Cooperative::withCount(['farmers', 'users'])->get();

        $commodities = Commodity::orderBy('name')->get();

        // 2. Hitung inbound (panen) per coop–komoditas–grade
        $inbound = DB::table('harvests as h')
            ->join('farmers as f', 'h.farmer_id', 'f.id')
            ->select('f.cooperative_id', 'h.commodity_id', 'h.grade', DB::raw('SUM(h.quantity) as qty_in'))
            ->groupBy('f.cooperative_id', 'h.commodity_id', 'h.grade')
            ->get();

        // 3. Hitung outbound (penjualan) per coop–komoditas–grade
        $outbound = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', 'o.id')
            ->join('buyers as b', 'o.buyer_id', 'b.id')
            ->join('users as u', 'b.user_id', 'u.id')
            ->select('u.cooperative_id', 'oi.commodity_id', 'oi.grade', DB::raw('SUM(oi.quantity) as qty_out'))
            ->groupBy('u.cooperative_id', 'oi.commodity_id', 'oi.grade')
            ->get();

        // 4. Gabungkan inbound & outbound jadi net stock
        $stocks = []; // [commodity_id => [coop_id => [grade => stock]]]
        foreach ($inbound as $row) {
            $stocks[$row->commodity_id][$row->cooperative_id][$row->grade] = $row->qty_in;
        }
        foreach ($outbound as $row) {
            $stocks[$row->commodity_id][$row->cooperative_id][$row->grade] =
                ($stocks[$row->commodity_id][$row->cooperative_id][$row->grade] ?? 0)
                - $row->qty_out;
        }

        // 5. Bangun array stok per komoditas
        $stokByCommodity = [];
        foreach ($commodities as $c) {
            $list = [];
            if (isset($stocks[$c->id])) {
                foreach ($stocks[$c->id] as $coopId => $grades) {
                    $coopName = DB::table('cooperatives')->where('id', $coopId)->value('name');
                    foreach ($grades as $grade => $qty) {
                        $list[] = [
                            'coop_name' => $coopName,
                            'grade'    => $grade,
                            'qty'      => max(0, $qty),
                        ];
                    }
                }
            }
            $stokByCommodity[] = [
                'commodity' => $c->name,
                'records'   => $list,
            ];
        }

        return view('dashboards.super', compact(
            'totalCoops',
            'totalUsers',
            'totalFarmers',
            'totalBuyers',
            'totalOrders',
            'totalHarvests',
            'omzet',
            'piutang',
            'stokGlobal',
            'stokByCommodity',
            'cooperatives'
        ));
    }
}
