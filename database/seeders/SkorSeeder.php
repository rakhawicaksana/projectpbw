<?php

namespace Database\Seeders;

use App\Models\Skor;
use Illuminate\Database\Seeder;

class SkorSeeder extends Seeder
{
    public function run(): void
    {
        // [inisial, kecerdasan, ketelitian, kecepatan, percaya diri, waktu (detik), kategori]
        $contoh = [
            ['ANT', 96, 92, 35,  62, 151, 'analitis'],
            ['SRI', 91, 85, 42,  70, 138, 'analitis'],
            ['MAS', 85, 78, 50,  88, 121, 'analitis'],
            ['DIT', 78, 42, 88, 126,  74, 'terlalu-pd'],
            ['BUD', 70, 50, 85, 140,  66, 'terlalu-pd'],
            ['RIA', 64, 55, 30,  45, 240, 'overthinker'],
            ['FAL', 52, 40, 60,  90, 133, 'korban-keadaan'],
            ['NIA', 44, 30, 55,  95, 129, 'korban-keadaan'],
            ['OJO', 31, 20, 70, 150,  98, 'terlalu-pd'],
            ['TIK', 15, 10, 65, 200, 105, 'otak-batu'],
        ];

        foreach ($contoh as [$inisial, $persen, $ketelitian, $kecepatan, $pd, $waktu, $kategori]) {
            Skor::create([
                'inisial'      => $inisial,
                'persen'       => $persen,
                'ketelitian'   => $ketelitian,
                'kecepatan'    => $kecepatan,
                'percaya_diri' => $pd,
                'waktu'        => $waktu,
                'kategori'     => $kategori,
            ]);
        }
    }
}
