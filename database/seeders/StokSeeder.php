<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // stok kategori Makanan (MKN)
            [
                'barang_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 100,
            ],
            [
                'barang_id' => 2,
                'user_id' => 2,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 200,
            ],

            // stok kategori Minuman (MIN)
            [
                'barang_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 150,
            ],
            [
                'barang_id' => 4,
                'user_id' => 2,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 180,
            ],

            // stok kategori Snack (SNK)
            [
                'barang_id' => 5,
                'user_id' => 3,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 280,
            ],
            [
                'barang_id' => 6,
                'user_id' => 1,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 130,
            ],

            // stok kategori Bumbu (BMB)
            [
                'barang_id' => 7,
                'user_id' => 2,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 110,
            ],
            [
                'barang_id' => 8,
                'user_id' => 3,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 90,
            ],

            // stok kategori Sabun (SBN)
            [
                'barang_id' => 9,
                'user_id' => 1,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 70,
            ],
            [
                'barang_id' => 10,
                'user_id' => 2,
                'stok_tanggal' => '2025-03-20',
                'stok_jumlah' => 60,
            ],
        ];

        DB::table('t_stok')->insert($data);
    }
}
