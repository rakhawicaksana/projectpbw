<?php

namespace App\Http;

/**
 * Menghitung hasil analisis dari catatan jawaban (PPT slide 9, 10, 13).
 *
 * Satu catatan per soal: ['level' => 1-3, 'benar' => bool, 'waktu_ms' => int, 'ganti' => int]
 *
 *  - kecerdasan   : % jawaban benar, soal level lebih tinggi bobotnya lebih besar
 *  - ketelitian   : % benar pada soal jebakan (level 2 dan 3)
 *  - kecepatan    : makin cepat rata-rata menjawab, makin tinggi (batas: config batas_waktu)
 *  - percaya_diri : % soal yang dijawab cepat tanpa ganti jawaban, dibanding % yang benar.
 *                   Di atas 100% artinya lebih yakin daripada kenyataannya.
 */
final class Analisis
{
    public static function hitung(array $catatan): array
    {
        $total = count($catatan);

        if ($total === 0) {
            throw new \InvalidArgumentException('Catatan jawaban kosong.');
        }

        $batasWaktu = (float) config('kebodohan.batas_waktu');
        $batasCepat = (float) config('kebodohan.batas_cepat');

        $bobotTotal = $bobotBenar = $jebakan = $jebakanBenar = $benar = $yakin = $ganti = $totalMs = 0;

        foreach ($catatan as $c) {
            $bobotTotal += $c['level'];
            $ganti += $c['ganti'];
            $totalMs += $c['waktu_ms'];

            if ($c['benar']) {
                $bobotBenar += $c['level'];
                $benar++;
            }
            if ($c['level'] >= 2) {
                $jebakan++;
                $jebakanBenar += $c['benar'] ? 1 : 0;
            }
            if ($c['waktu_ms'] / 1000 <= $batasCepat && $c['ganti'] === 0) {
                $yakin++;
            }
        }

        $rataWaktu = $totalMs / $total / 1000;
        $rataGanti = $ganti / $total;

        $kecerdasan = (int) round($bobotBenar / $bobotTotal * 100);
        $ketelitian = $jebakan > 0 ? (int) round($jebakanBenar / $jebakan * 100) : $kecerdasan;
        $kecepatan = (int) round(100 * (1 - min($rataWaktu, $batasWaktu) / $batasWaktu));

        $persenBenar = $benar / $total * 100;
        $persenYakin = $yakin / $total * 100;
        $percayaDiri = $persenBenar > 0
            ? (int) min(200, round($persenYakin / $persenBenar * 100))
            : ($persenYakin > 0 ? 200 : 0);

        return [
            'persen'       => $kecerdasan,
            'ketelitian'   => $ketelitian,
            'kecepatan'    => $kecepatan,
            'percaya_diri' => $percayaDiri,
            'waktu'        => (int) min(9999, round($totalMs / 1000)),
            'benar'        => $benar,
            'total'        => $total,
            'kategori'     => self::kategori($kecerdasan, $rataWaktu, $rataGanti),
        ];
    }

    private static function kategori(int $kecerdasan, float $rataWaktu, float $rataGanti): string
    {
        $a = config('kebodohan.ambang');

        if ($kecerdasan <= $a['otak_batu_maks']) {
            return 'otak-batu';
        }
        if ($rataGanti >= $a['overthinker_ganti'] || $rataWaktu >= $a['overthinker_waktu']) {
            return 'overthinker';
        }
        if ($rataWaktu <= config('kebodohan.batas_cepat') && $kecerdasan < $a['pd_maks_cerdas']) {
            return 'terlalu-pd';
        }
        if ($kecerdasan < $a['korban_maks']) {
            return 'korban-keadaan';
        }

        return 'analitis';
    }
}
