<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Cooperatives with varied grades
        $coopData = [
            ['Koperasi Tani Makmur', 'Jl. Raya No.1, Desa Sukamaju',       ['A', 'B', 'C']],
            ['Agro Sejahtera',       'Jl. Kebon Raya No.5, Desa Mekar',       ['A', 'B']],
            ['Pertanian Maju',       'Jl. Sawah Indah No.10, Desa Makmur',    ['B', 'C']],
            ['Koperasi Pelita Desa', 'Jl. Kenangan No.7, Desa Harapan',      ['A']],
            ['Bumi Mandiri',         'Jl. Sawah Makmur No.12, Desa Sejahtera', ['C']],
            ['Sawah Subur',          'Jl. Sawah Lestari No.15, Desa Damai',   ['A', 'C']],
        ];
        $coopIds = [];
        $coopGrades = [];
        foreach ($coopData as list($name, $addr, $grades)) {
            $id = DB::table('cooperatives')->insertGetId([
                'name'       => $name,
                'address'    => $addr,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $coopIds[]      = $id;
            $coopGrades[$id] = $grades;
        }

        // 2. Roles
        $roles = ['Super Admin', 'Admin Koperasi', 'Petugas Lapangan', 'Petani', 'Pembeli'];
        $roleIds = [];
        foreach ($roles as $r) {
            $roleIds[$r] = DB::table('roles')->insertGetId([
                'name'        => $r,
                'description' => $r,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // 3. Users & Farmers / Buyers
        // a) Owner, Admin, Field Officer
        $baseUsers = [
            ['Owner',            'owner@example.com',       '08110000000', null,             $roleIds['Super Admin']],
            ['Admin Makmur',     'admintani@example.com',   '081122233344', $coopIds[0],     $roleIds['Admin Koperasi']],
            ['Petugas Lapangan', 'petugas1@koperasi.com',   '081133344455', $coopIds[0],     $roleIds['Petugas Lapangan']],
        ];
        foreach ($baseUsers as list($name, $email, $phone, $coopId, $roleId)) {
            DB::table('users')->insert([
                'name'           => $name,
                'email'          => $email,
                'password'       => Hash::make('password'),
                'phone'          => $phone,
                'cooperative_id' => $coopId,
                'role_id'        => $roleId,
                'address'        => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // b) Farmers: 5 per cooperative
        foreach ($coopIds as $coopId) {
            for ($i = 1; $i <= 5; $i++) {
                $uid = DB::table('users')->insertGetId([
                    'name'           => "Petani {$coopId}-{$i}",
                    'email'          => "petani{$coopId}_{$i}@example.com",
                    'password'       => Hash::make('password'),
                    'phone'          => '08123' . mt_rand(10000, 99999),
                    'cooperative_id' => $coopId,
                    'role_id'        => $roleIds['Petani'],
                    'address'        => null,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
                DB::table('farmers')->insert([
                    'user_id'        => $uid,
                    'cooperative_id' => $coopId,
                    'balance'        => 0,
                    'join_date'      => $now->toDateString(),
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }
        }

        // c) Buyers: 2 sample
        $buyers = [
            ['Pembeli A',  'buyerA@example.com', 'INDIVIDUAL', null],
            ['Restoran B', 'buyerB@example.com', 'B2B',        'Restoran Makmur'],
        ];
        foreach ($buyers as list($name, $email, $type, $company)) {
            $uid = DB::table('users')->insertGetId([
                'name'           => $name,
                'email'          => $email,
                'password'       => Hash::make('password'),
                'phone'          => '08124' . mt_rand(10000, 99999),
                'cooperative_id' => $coopIds[0],
                'role_id'        => $roleIds['Pembeli'],
                'address'        => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            DB::table('buyers')->insert([
                'user_id'      => $uid,
                'type'         => $type,
                'company_name' => $company,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // 4. Commodities
        $commodities = [
            ['Beras',        'kg'],
            ['Kopi',         'kg'],
            ['Jagung',       'kg'],
            ['Kentang',      'kg'],
            ['Bawang Merah', 'kg'],
        ];
        $commodityIds = [];
        foreach ($commodities as list($name, $unit)) {
            $commodityIds[$name] = DB::table('commodities')->insertGetId([
                'name'       => $name,
                'unit'       => $unit,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 5. Initial global stocks (all grades)
        foreach ($commodityIds as $cid) {
            foreach (['A', 'B', 'C'] as $grade) {
                DB::table('commodity_stocks')->insert([
                    'commodity_id' => $cid,
                    'grade'        => $grade,
                    'quantity'     => rand(50, 200),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        // 6. Harvests: only for grades each cooperative provides
        foreach ($coopIds as $coopId) {
            foreach ($commodityIds as $name => $cid) {
                foreach ($coopGrades[$coopId] as $grade) {
                    switch ($name) {
                        case 'Beras':
                            $qty = rand(500, 1500);
                            $price = rand(10000, 14000);
                            break;
                        case 'Kopi':
                            $qty = rand(100, 300);
                            $price = rand(40000, 70000);
                            break;
                        case 'Jagung':
                            $qty = rand(300, 800);
                            $price = rand(4000, 6000);
                            break;
                        case 'Kentang':
                            $qty = rand(200, 600);
                            $price = rand(6000, 10000);
                            break;
                        case 'Bawang Merah':
                            $qty = rand(100, 400);
                            $price = rand(20000, 35000);
                            break;
                        default:
                            $qty = rand(50, 150);
                            $price = rand(4000, 7000);
                    }
                    $total = $qty * $price;
                    $farm  = DB::table('farmers')
                        ->where('cooperative_id', $coopId)
                        ->inRandomOrder()
                        ->first();

                    DB::table('harvests')->insert([
                        'farmer_id'    => $farm->id,
                        'commodity_id' => $cid,
                        'grade'        => $grade,
                        'quantity'     => $qty,
                        'unit_price'   => $price,
                        'total_amount' => $total,
                        'harvest_date' => $now->toDateString(),
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]);

                    // update global stocks & farmer balance
                    DB::table('commodity_stocks')
                        ->where('commodity_id', $cid)
                        ->where('grade', $grade)
                        ->increment('quantity', $qty);

                    DB::table('farmers')
                        ->where('id', $farm->id)
                        ->increment('balance', $total);
                }
            }
        }

        // 7. Orders & Payments
        $allBuyers = DB::table('buyers')->pluck('user_id')->toArray();
        shuffle($allBuyers);
        $toProcess = array_slice($allBuyers, 0, min(3, count($allBuyers)));

        foreach ($toProcess as $bu) {
            $buyer = DB::table('buyers')->where('user_id', $bu)->first();
            $orderId = DB::table('orders')->insertGetId([
                'buyer_id'     => $buyer->id,
                'order_date'   => $now->toDateString(),
                'total_amount' => 0,
                'payment_status' => 'PAID',
                'due_date'     => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            $orderTotal = 0;
            $comms = array_keys($commodityIds);
            shuffle($comms);
            foreach (array_slice($comms, 0, 2) as $cName) {
                $cid        = $commodityIds[$cName];
                $grade      = ['A', 'B', 'C'][array_rand(['A', 'B', 'C'])];
                $stockAvail = DB::table('commodity_stocks')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->value('quantity');
                if ($stockAvail < 1) continue;
                switch ($cName) {
                    case 'Beras':
                        $qty = rand(50, min(200, $stockAvail));
                        break;
                    case 'Kopi':
                        $qty = rand(5,  min(50,  $stockAvail));
                        break;
                    case 'Jagung':
                        $qty = rand(30, min(150, $stockAvail));
                        break;
                    case 'Kentang':
                        $qty = rand(20, min(100, $stockAvail));
                        break;
                    case 'Bawang Merah':
                        $qty = rand(10, min(50,  $stockAvail));
                        break;
                    default:
                        $qty = rand(1,  min(10,  $stockAvail));
                }
                $unitPrice  = DB::table('harvests')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->inRandomOrder()
                    ->value('unit_price');
                $totalPrice = $qty * $unitPrice;
                $orderTotal += $totalPrice;

                DB::table('order_items')->insert([
                    'order_id'     => $orderId,
                    'commodity_id' => $cid,
                    'grade'        => $grade,
                    'quantity'     => $qty,
                    'price'        => $unitPrice,
                    'total_price'  => $totalPrice,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
                DB::table('commodity_stocks')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->decrement('quantity', $qty);
            }
            DB::table('orders')->where('id', $orderId)
                ->update(['total_amount' => $orderTotal]);
            DB::table('order_payments')->insert([
                'order_id'     => $orderId,
                'payment_date' => $now->toDateString(),
                'amount'       => $orderTotal,
                'method'       => 'Cash',
                'note'         => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}
