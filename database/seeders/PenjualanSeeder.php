<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'pembeli' => 'Esti Kuniawati',
                'penjualan_kode' => 'J001',
                'penjualan_tanggal' => Carbon::parse('2025-02-10 11:15:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Fauziah Rahmanda',
                'penjualan_kode' => 'J002',
                'penjualan_tanggal' => Carbon::parse('2025-02-11 12:30:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Azaria Cindy',
                'penjualan_kode' => 'J003',
                'penjualan_tanggal' => Carbon::parse('2025-02-12 14:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Alfiyah Nadatul',
                'penjualan_kode' => 'J004',
                'penjualan_tanggal' => Carbon::parse('2025-02-15 16:20:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Azkiya Putri',
                'penjualan_kode' => 'J005',
                'penjualan_tanggal' => Carbon::parse('2025-02-11 17:10:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Fanan Muhammad',
                'penjualan_kode' => 'J006',
                'penjualan_tanggal' => Carbon::parse('2025-02-12 15:45:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Rhsi Lintang',
                'penjualan_kode' => 'J007',
                'penjualan_tanggal' => Carbon::parse('2025-02-13 14:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Iqbal Maulana',
                'penjualan_kode' => 'J008',
                'penjualan_tanggal' => Carbon::parse('2025-02-14 13:15:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Agung Kurniawan',
                'penjualan_kode' => 'J009',
                'penjualan_tanggal' => Carbon::parse('2025-02-15 10:30:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Davis Mahendra',
                'penjualan_kode' => 'J010',
                'penjualan_tanggal' => Carbon::parse('2025-02-16 08:45:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        
        DB::table('t_penjualan')->insert($data);
    }
}
