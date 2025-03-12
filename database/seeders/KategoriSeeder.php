<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1, 
                'kategori_kode' => 'SBK',
                'kategori_nama' => 'Sembako',
            ],
            [
                'kategori_id' => 2, 
                'kategori_kode' => 'SNK',
                'kategori_nama' => 'Snack',
            ],
            [
                'kategori_id' => 3, 
                'kategori_kode' => 'MIN',
                'kategori_nama' => 'Minuman',
            ],
            [
                'kategori_id' => 4, 
                'kategori_kode' => 'PDG',
                'kategori_nama' => 'Puding',
            ],
            [
                'kategori_id' => 5, 
                'kategori_kode' => 'MYK',
                'kategori_nama' => 'Minyak',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
