<?php

namespace App\Http\Controllers;

use App\Http\Analisis;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Alur quiz (PPT slide 15): Mulai -> Pertanyaan -> Jawab -> Cek (benar/salah) -> Next -> Selesai -> Analisis -> Hasil.
 * Semua status (soal terpilih, kunci jawaban, waktu) disimpan di session server,
 * jadi browser tidak bisa mengintip jawaban atau memalsukan waktu.
 */
class TesController extends Controller
{
    /** Mulai tes baru: pilih soal acak per level dan acak urutan opsinya. */
    public function index(): View
    {
        $bank = config('kebodohan.soal');
        $pilih = [];

        foreach (config('kebodohan.komposisi') as $level => $jumlah) {
            $ids = array_keys(array_filter($bank, fn ($s) => $s['level'] === $level));
            shuffle($ids);

            foreach (array_slice($ids, 0, $jumlah) as $id) {
                $urut = array_keys($bank[$id]['opsi']);
                shuffle($urut);
                $pilih[] = ['id' => $id, 'urut' => $urut];
            }
        }

        session(['tes' => [
            'soal'       => $pilih,
            'i'          => 0,
            'catatan'    => [],
            'mulai_soal' => null,
            'pd'         => 100,
            'selesai'    => false,
        ]]);
        session()->forget('hasil');

        return view('tes', [
            'total'   => count($pilih),
            'tingkat' => config('kebodohan.tingkat'),
        ]);
    }

    /** JSON soal yang sedang aktif. Timer server mulai di sini. Kunci jawaban tidak ikut dikirim. */
    public function soal(): JsonResponse
    {
        $tes = session('tes');

        if (! $tes) {
            return response()->json(['pesan' => 'Tes belum dimulai.'], 409);
        }
        if ($tes['selesai']) {
            return response()->json(['selesai' => true]);
        }

        $item = $tes['soal'][$tes['i']];
        $s = config('kebodohan.soal')[$item['id']];

        // kalau halaman di-refresh, timer tidak diulang dari nol
        if ($tes['mulai_soal'] === null) {
            $tes['mulai_soal'] = microtime(true);
            session(['tes' => $tes]);
        }

        return response()->json([
            'nomor' => $tes['i'] + 1,
            'total' => count($tes['soal']),
            'level' => $s['level'],
            'tanya' => $s['tanya'],
            'opsi'  => array_map(fn ($k) => $s['opsi'][$k], $item['urut']),
            'pd'    => $tes['pd'],
        ]);
    }

    /** Terima jawaban, cek, kembalikan benar/salah + penjelasan. Body: pilihan (posisi opsi), ganti (berapa kali ganti jawaban). */
    public function jawab(Request $request): JsonResponse
    {
        $tes = session('tes');

        if (! $tes || $tes['selesai'] || $tes['mulai_soal'] === null) {
            return response()->json(['pesan' => 'Tidak ada soal aktif.'], 409);
        }

        $data = $request->validate([
            'pilihan' => ['required', 'integer', 'min:0'],
            'ganti'   => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);

        $item = $tes['soal'][$tes['i']];
        $s = config('kebodohan.soal')[$item['id']];

        if (! isset($item['urut'][$data['pilihan']])) {
            return response()->json(['pesan' => 'Pilihan tidak valid.'], 422);
        }

        $waktuMs = (int) round((microtime(true) - $tes['mulai_soal']) * 1000);
        $benar = $item['urut'][$data['pilihan']] === $s['benar'];
        $turun = 0;

        if (! $benar) {
            $turun = config('kebodohan.penurunan_pd')[$s['level']] ?? 0;
            $tes['pd'] = max(0, $tes['pd'] - $turun);
        }

        $tes['catatan'][] = [
            'level'    => $s['level'],
            'benar'    => $benar,
            'waktu_ms' => $waktuMs,
            'ganti'    => (int) ($data['ganti'] ?? 0),
        ];
        $tes['i']++;
        $tes['mulai_soal'] = null;
        $tes['selesai'] = $tes['i'] >= count($tes['soal']);
        session(['tes' => $tes]);

        return response()->json([
            'benar'          => $benar,
            'posisi_benar'   => array_search($s['benar'], $item['urut'], true),
            'penjelasan'     => $s['penjelasan'],
            'turun'          => $turun,
            'pd'             => $tes['pd'],
            'waktu_ms'       => $waktuMs,
            'selesai'        => $tes['selesai'],
        ]);
    }

    /**
     * Halaman hasil (4 metrik + kategori).
     * Variabel view: $persen, $ketelitian, $kecepatan, $percaya_diri, $waktu, $benar, $total,
     * $kategori (kunci) dan $info (nama, deskripsi, penutup, sprite).
     */
    public function hasil(): View|RedirectResponse
    {
        $tes = session('tes');

        if (! $tes || ! $tes['selesai']) {
            return redirect()->route('tes');
        }

        $hasil = Analisis::hitung($tes['catatan']);
        session(['hasil' => $hasil]);

        return view('hasil-arcade', $hasil + [
            'info' => config('kebodohan.kategori')[$hasil['kategori']],
        ]);
    }
}
