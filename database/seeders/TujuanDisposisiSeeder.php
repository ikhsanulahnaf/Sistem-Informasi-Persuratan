<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TujuanDisposisi;

class TujuanDisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tujuans = [
            ['nama' => 'WAREK APK', 'urutan' => 1],
            ['nama' => 'WAREK BKS', 'urutan' => 2],
            ['nama' => 'SPMI', 'urutan' => 3],
            ['nama' => 'PA', 'urutan' => 4],
            ['nama' => 'PKA', 'urutan' => 5],
            ['nama' => 'PRPM', 'urutan' => 6],
            ['nama' => 'PPMB', 'urutan' => 7],
            ['nama' => 'PDSI', 'urutan' => 8],
            ['nama' => 'PI2B', 'urutan' => 9],
            ['nama' => 'BSDMO', 'urutan' => 10],
            ['nama' => 'BPDK', 'urutan' => 11],
            ['nama' => 'BPK', 'urutan' => 12],
            ['nama' => 'BKH', 'urutan' => 13],
            ['nama' => 'T. ELEKTRO', 'urutan' => 14],
            ['nama' => 'T. MESIN', 'urutan' => 15],
            ['nama' => 'T. KIMIA', 'urutan' => 16],
            ['nama' => 'T. INDUSTRI', 'urutan' => 17],
            ['nama' => 'T. INFORMATIKA', 'urutan' => 18],
            ['nama' => 'T. SIPIL', 'urutan' => 19],
            ['nama' => 'MANAJEMEN', 'urutan' => 20],
            ['nama' => 'PWK', 'urutan' => 21],
            ['nama' => 'ARSITEKTUR', 'urutan' => 22],
            ['nama' => 'TIP', 'urutan' => 23],
            ['nama' => 'PSPPI', 'urutan' => 24],
            ['nama' => 'Sekr. Rektor', 'urutan' => 25],
        ];

        foreach ($tujuans as $tujuan) {
            TujuanDisposisi::updateOrCreate(
                ['nama' => $tujuan['nama']],
                [
                    'urutan' => $tujuan['urutan'],
                    'is_active' => true,
                ]
            );
        }
    }
}
