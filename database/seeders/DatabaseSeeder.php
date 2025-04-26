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

        // 1. Cooperative
        $coopId = DB::table('cooperatives')->insertGetId([
            'name'       => 'Koperasi Tani Makmur',
            'address'    => 'Jl. Raya No.1, Desa Sukamaju',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. Roles
        $roles = ['Super Admin', 'Admin Koperasi', 'Petugas Lapangan', 'Petani', 'Pembeli'];
        $roleIds = [];
        foreach ($roles as $name) {
            $roleIds[$name] = DB::table('roles')->insertGetId([
                'name'        => $name,
                'description' => $name,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // 3. Users & Farmers/Buyers
        // 3a. Super Admin, Admin Koperasi, Petugas Lapangan
        $baseUsers = [
            [
                'name'           => 'Owner',
                'email'          => 'owner@example.com',
                'password'       => Hash::make('password'),
                'phone'          => '0811111111',
                'cooperative_id' => null,
                'role_id'        => $roleIds['Super Admin'],
            ],
            [
                'name'           => 'Admin Koperasi',
                'email'          => 'admin@koperasi.com',
                'password'       => Hash::make('password'),
                'phone'          => '0811111112',
                'cooperative_id' => $coopId,
                'role_id'        => $roleIds['Admin Koperasi'],
            ],
            [
                'name'           => 'Petugas Lapangan',
                'email'          => 'petugas1@koperasi.com',
                'password'       => Hash::make('password'),
                'phone'          => '0811111113',
                'cooperative_id' => $coopId,
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

        // 3b. Petani (5 orang)
        for ($i = 1; $i <= 5; $i++) {
            $email = "petani{$i}@example.com";
            $uid   = DB::table('users')->insertGetId([
                'name'           => "Petani {$i}",
                'email'          => $email,
                'password'       => Hash::make('password'),
                'phone'          => '08111111' . (30 + $i),
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
                'join_date'      => '2025-01-01',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // 3c. Pembeli (2 orang)
        $buyerData = [
            [
                'name'           => 'Pembeli X',
                'email'          => 'buyerx@example.com',
                'password'       => Hash::make('password'),
                'phone'          => '0811111121',
                'type'           => 'B2B',
                'company_name'   => 'Restoran Sederhana',
            ],
            [
                'name'           => 'Pembeli Y',
                'email'          => 'buyery@example.com',
                'password'       => Hash::make('password'),
                'phone'          => '0811111122',
                'type'           => 'INDIVIDUAL',
                'company_name'   => null,
            ],
        ];
        foreach ($buyerData as $b) {
            $uid = DB::table('users')->insertGetId([
                'name'           => $b['name'],
                'email'          => $b['email'],
                'password'       => $b['password'],
                'phone'          => $b['phone'],
                'cooperative_id' => $coopId,
                'role_id'        => $roleIds['Pembeli'],
                'address'        => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            DB::table('buyers')->insert([
                'user_id'      => $uid,
                'type'         => $b['type'],
                'company_name' => $b['company_name'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // 4. Commodities & Stocks
        $commodities = [
            ['name' => 'Beras', 'unit' => 'kg'],
            ['name' => 'Kopi',  'unit' => 'kg'],
            ['name' => 'Jagung', 'unit' => 'kg'],
        ];
        $commodityIds = [];
        foreach ($commodities as $c) {
            $commodityIds[$c['name']] = DB::table('commodities')->insertGetId([
                'name'       => $c['name'],
                'unit'       => $c['unit'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        foreach ($commodityIds as $cid) {
            foreach (['A', 'B', 'C'] as $grade) {
                DB::table('commodity_stocks')->insert([
                    'commodity_id' => $cid,
                    'grade'        => $grade,
                    'quantity'     => 0,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        // 5. Harvests (contoh panen awal)
        $harvests = [
            ['email' => 'petani1@example.com', 'comm' => 'Beras', 'grade' => 'A', 'qty' => 100, 'price' => 5000, 'date' => '2025-02-01'],
            ['email' => 'petani2@example.com', 'comm' => 'Beras', 'grade' => 'B', 'qty' => 50, 'price' => 4000, 'date' => '2025-02-01'],
            ['email' => 'petani3@example.com', 'comm' => 'Kopi', 'grade' => 'A', 'qty' => 30, 'price' => 30000, 'date' => '2025-02-02'],
        ];
        foreach ($harvests as $h) {
            $user = DB::table('users')->where('email', $h['email'])->first();
            $farm = DB::table('farmers')->where('user_id', $user->id)->first();
            $total = $h['qty'] * $h['price'];

            DB::table('harvests')->insert([
                'farmer_id'     => $farm->id,
                'commodity_id'  => $commodityIds[$h['comm']],
                'grade'         => $h['grade'],
                'quantity'      => $h['qty'],
                'unit_price'    => $h['price'],
                'total_amount'  => $total,
                'harvest_date'  => $h['date'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);

            // update stok & saldo
            DB::table('commodity_stocks')
                ->where('commodity_id', $commodityIds[$h['comm']])
                ->where('grade', $h['grade'])
                ->increment('quantity', $h['qty']);

            DB::table('farmers')
                ->where('id', $farm->id)
                ->increment('balance', $total);
        }

        // 6. Orders, Order Items & Payments
        // Order 1: Partial payment
        $buyerX = DB::table('buyers')
            ->where('user_id', DB::table('users')->where('email', 'buyerx@example.com')->value('id'))
            ->first();

        $order1Id = DB::table('orders')->insertGetId([
            'buyer_id'       => $buyerX->id,
            'order_date'     => '2025-03-01',
            'total_amount'   => 295000,
            'payment_status' => 'PARTIAL',
            'due_date'       => '2025-03-15',
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        $items1 = [
            ['comm' => 'Beras', 'grade' => 'A', 'qty' => 20, 'price' => 6000],
            ['comm' => 'Kopi', 'grade' => 'A', 'qty' => 5, 'price' => 35000],
        ];
        foreach ($items1 as $it) {
            $cid   = $commodityIds[$it['comm']];
            $total = $it['qty'] * $it['price'];

            DB::table('order_items')->insert([
                'order_id'     => $order1Id,
                'commodity_id' => $cid,
                'grade'        => $it['grade'],
                'quantity'     => $it['qty'],
                'price'        => $it['price'],
                'total_price'  => $total,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            DB::table('commodity_stocks')
                ->where('commodity_id', $cid)
                ->where('grade', $it['grade'])
                ->decrement('quantity', $it['qty']);
        }

        DB::table('order_payments')->insert([
            'order_id'    => $order1Id,
            'payment_date' => '2025-03-10',
            'amount'      => 150000,
            'method'      => 'Transfer',
            'note'        => null,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // Order 2: Paid in full
        $buyerY = DB::table('buyers')
            ->where('user_id', DB::table('users')->where('email', 'buyery@example.com')->value('id'))
            ->first();

        $order2Id = DB::table('orders')->insertGetId([
            'buyer_id'       => $buyerY->id,
            'order_date'     => '2025-03-05',
            'total_amount'   => 50000,
            'payment_status' => 'PAID',
            'due_date'       => null,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        DB::table('order_items')->insert([
            'order_id'     => $order2Id,
            'commodity_id' => $commodityIds['Beras'],
            'grade'        => 'B',
            'quantity'     => 10,
            'price'        => 5000,
            'total_price'  => 50000,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        DB::table('commodity_stocks')
            ->where('commodity_id', $commodityIds['Beras'])
            ->where('grade', 'B')
            ->decrement('quantity', 10);

        DB::table('order_payments')->insert([
            'order_id'    => $order2Id,
            'payment_date' => '2025-03-05',
            'amount'      => 50000,
            'method'      => 'Cash',
            'note'        => null,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }
}
