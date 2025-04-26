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

        //
        // 1. Cooperatives (3 koperasi)
        //
        $coopData = [
            ['Koperasi Tani Makmur', 'Jl. Raya No.1, Desa Sukamaju'],
            ['Agro Sejahtera',       'Jl. Kebon Raya No.5, Desa Mekar'],
            ['Pertanian Maju',       'Jl. Sawah Indah No.10, Desa Makmur'],
        ];
        $coopIds = [];
        foreach ($coopData as list($name, $addr)) {
            $coopIds[] = DB::table('cooperatives')->insertGetId([
                'name'       => $name,
                'address'    => $addr,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        //
        // 2. Roles
        //
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

        //
        // 3. Users & Farmers / Buyers
        //

        // a) Owner + Admin Koperasi + Petugas Lapangan
        $baseUsers = [
            [
                'name'           => 'Owner',
                'email'          => 'owner@example.com',
                'password'       => Hash::make('password'),
                'phone'          => '08110000000',
                'cooperative_id' => null,
                'role_id'        => $roleIds['Super Admin'],
            ],
            [
                'name'           => 'Admin Makmur',
                'email'          => 'admintani@example.com',
                'password'       => Hash::make('password'),
                'phone'          => '081122233344',
                'cooperative_id' => $coopIds[0],
                'role_id'        => $roleIds['Admin Koperasi'],
            ],
            [
                'name'           => 'Petugas Lapangan',
                'email'          => 'petugas1@koperasi.com',
                'password'       => Hash::make('password'),
                'phone'          => '081133344455',
                'cooperative_id' => $coopIds[0],
                'role_id'        => $roleIds['Petugas Lapangan'],
            ],
        ];
        foreach ($baseUsers as $u) {
            DB::table('users')->insert(array_merge($u, [
                'address'    => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // b) Petani: 5 per koperasi
        foreach ($coopIds as $idx => $coopId) {
            for ($i = 1; $i <= 5; $i++) {
                $email = "petani{$idx}_{$i}@example.com";
                $uid   = DB::table('users')->insertGetId([
                    'name'           => "Petani {$idx}-{$i}",
                    'email'          => $email,
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

        // c) Pembeli: 2 orang
        $buyers = [
            ['Pembeli A', 'buyerA@example.com', 'INDIVIDUAL', null],
            ['Restoran B', 'buyerB@example.com', 'B2B', 'Restoran Makmur'],
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

        //
        // 4. Commodities
        //
        $commodities = [
            ['Beras', 'kg'],
            ['Kopi', 'kg'],
            ['Jagung', 'kg'],
            ['Kentang', 'kg'],
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

        //
        // 5. Commodity Stocks (global) &gt;0
        //
        foreach ($commodityIds as $cid) {
            foreach (['A', 'B', 'C'] as $grade) {
                DB::table('commodity_stocks')->insert([
                    'commodity_id' => $cid,
                    'grade'        => $grade,
                    'quantity'     => rand(10, 100),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        //
        // 6. Harvests: setiap koperasi men‐panen tiap komoditas–grade
        //
        foreach ($coopIds as $coopId) {
            foreach ($commodityIds as $cid) {
                foreach (['A', 'B', 'C'] as $grade) {
                    $qty   = rand(50, 150);
                    $price = rand(4000, 7000);
                    $total = $qty * $price;
                    $farm  = DB::table('farmers')
                        ->where('cooperative_id', $coopId)
                        ->inRandomOrder()->first();

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

        //
        // 7. Orders & Sales: 3 order acak bisnis penuh bayar
        //
        // Ambil semua user_id buyers
        $allBuyers = DB::table('buyers')->pluck('user_id')->toArray();
        shuffle($allBuyers);

        // Batasi maksimal 3 order, tapi jangan melebihi jumlah buyer
        $toProcess = array_slice($allBuyers, 0, min(3, count($allBuyers)));

        foreach ($toProcess as $buyerUserId) {
            $buyer = DB::table('buyers')->where('user_id', $buyerUserId)->first();

            // Buat header order (sementara total_amount=0, nanti diupdate)
            $orderId = DB::table('orders')->insertGetId([
                'buyer_id'       => $buyer->id,
                'order_date'     => $now->toDateString(),
                'total_amount'   => 0,
                'payment_status' => 'PAID',
                'due_date'       => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $orderTotal = 0;

            // Ambil 2 komoditas random
            $bulkCommKeys = array_keys($commodityIds);
            shuffle($bulkCommKeys);
            $selectedComms = array_slice($bulkCommKeys, 0, 2);

            foreach ($selectedComms as $commName) {
                $cid   = $commodityIds[$commName];
                $grade = ['A', 'B', 'C'][array_rand(['A', 'B', 'C'])];

                // Cek stok global untuk grade ini
                $stockAvail = DB::table('commodity_stocks')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->value('quantity');

                if ($stockAvail < 1) {
                    continue; // skip jika kosong
                }

                // Qty pesanan random antara 1 dan min(10, stokAvail)
                $qty = rand(1, min(10, $stockAvail));

                // Ambil unit_price dari salah satu panen
                $unitPrice = DB::table('harvests')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->inRandomOrder()
                    ->value('unit_price');

                $totalPrice = $qty * $unitPrice;
                $orderTotal += $totalPrice;

                // Insert order item
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

                // Kurangi stok global
                DB::table('commodity_stocks')
                    ->where('commodity_id', $cid)
                    ->where('grade', $grade)
                    ->decrement('quantity', $qty);
            }

            // Update total_amount pada header order
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total_amount' => $orderTotal]);

            // Catat payment
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
