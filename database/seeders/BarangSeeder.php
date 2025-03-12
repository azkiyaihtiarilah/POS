<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // kategori Sembako (SBK)
            [
                'barang_id' => 1,
                'kategori_id' => 1, 
                'barang_kode' => 'SBK001',
                'barang_nama' => 'Beras',
                'harga_beli' => 12000,
                'harga_jual' => 14000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1, 
                'barang_kode' => 'SBK002',
                'barang_nama' => 'Mie Instan',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
            ],

            // kategori Snack (SNK)
            [
                'barang_id' => 3,
                'kategori_id' => 2, 
                'barang_kode' => 'SNK001',
                'barang_nama' => 'Chitato 200gr',
                'harga_beli' => 5000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2, 
                'barang_kode' => 'SNK002',
                'barang_nama' => 'Taro 200gr',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
            ],

            // kategori Minuman (MIN)
            [
                'barang_id' => 5,
                'kategori_id' => 3, 
                'barang_kode' => 'MIN001',
                'barang_nama' => 'Ultra Milk 250ml',
                'harga_beli' => 5000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3, 
                'barang_kode' => 'MIN002',
                'barang_nama' => 'Pocarisweet 500ml',
                'harga_beli' => 7000,
                'harga_jual' => 9000,
            ],

            // kategori Pudding (PDG)
            [
                'barang_id' => 7,
                'kategori_id' => 4, 
                'barang_kode' => 'PDG001',
                'barang_nama' => 'Nutrijel 30gr',
                'harga_beli' => 4000,
                'harga_jual' => 8000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4, 
                'barang_kode' => 'PDG002',
                'barang_nama' => 'Mama recipe',
                'harga_beli' => 12000,
                'harga_jual' => 14000,
            ],

            // kategori Minyak (MYK)
            [
                'barang_id' => 9,
                'kategori_id' => 5, 
                'barang_kode' => 'MYK001',
                'barang_nama' => 'Filma 1 ltr',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5, 
                'barang_kode' => 'MYK002',
                'barang_nama' => 'Sunco 1 ltr',
                'harga_beli' => 18000,
                'harga_jual' => 20000,
            ],
        ];

        DB::table('m_barang')->insert($data);
    }
}
