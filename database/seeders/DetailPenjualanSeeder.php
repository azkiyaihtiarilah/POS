<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DetailPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Penjualan 1
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 14000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 1, 'barang_id' => 2, 'harga' => 5000, 'jumlah' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 1, 'barang_id' => 3, 'harga' => 7000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 2
            ['penjualan_id' => 2, 'barang_id' => 4, 'harga' => 5000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 2, 'barang_id' => 5, 'harga' => 7000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 2, 'barang_id' => 6, 'harga' => 9000, 'jumlah' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 3
            ['penjualan_id' => 3, 'barang_id' => 7, 'harga' => 8000, 'jumlah' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 3, 'barang_id' => 8, 'harga' => 14000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 3, 'barang_id' => 9, 'harga' => 17000, 'jumlah' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 4
            ['penjualan_id' => 4, 'barang_id' => 10, 'harga' => 20000, 'jumlah' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 4, 'barang_id' => 1, 'harga' => 14000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 4, 'barang_id' => 2, 'harga' => 5000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 5
            ['penjualan_id' => 5, 'barang_id' => 3, 'harga' => 7000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 5, 'barang_id' => 4, 'harga' => 5000, 'jumlah' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 5, 'barang_id' => 5, 'harga' => 7000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 6
            ['penjualan_id' => 6, 'barang_id' => 6, 'harga' => 9000, 'jumlah' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 6, 'barang_id' => 7, 'harga' => 8000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 6, 'barang_id' => 8, 'harga' => 14000, 'jumlah' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 7
            ['penjualan_id' => 7, 'barang_id' => 9, 'harga' => 17000, 'jumlah' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 7, 'barang_id' => 10, 'harga' => 20000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 7, 'barang_id' => 1, 'harga' => 14000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Penjualan 8
            ['penjualan_id' => 8, 'barang_id' => 2, 'harga' => 5000, 'jumlah' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 8, 'barang_id' => 3, 'harga' => 7000, 'jumlah' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['penjualan_id' => 8, 'barang_id' => 4, 'harga' => 5000, 'jumlah' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
