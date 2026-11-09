<?php

namespace Tests\Feature;

use Tests\TestCase;

class TesAlurTest extends TestCase
{
    /** Sesi tes buatan sendiri: opsi tidak diacak supaya mudah dicek. */
    private function sesi(int $jumlah = 2): array
    {
        $bank = config('kebodohan.soal');
        $soal = [];

        foreach (array_slice(array_keys($bank), 0, $jumlah) as $id) {
            $soal[] = ['id' => $id, 'urut' => array_keys($bank[$id]['opsi'])];
        }

        return ['tes' => [
            'soal' => $soal, 'i' => 0, 'catatan' => [],
            'mulai_soal' => null, 'pd' => 100, 'selesai' => false,
        ]];
    }

    public function test_soal_tidak_membocorkan_kunci_jawaban(): void
    {
        $this->withSession($this->sesi())
            ->getJson('/tes/soal')
            ->assertOk()
            ->assertJsonStructure(['nomor', 'total', 'level', 'tanya', 'opsi', 'pd'])
            ->assertJsonMissingPath('benar')
            ->assertJsonMissingPath('penjelasan');
    }

    public function test_jawaban_benar_tidak_menurunkan_percaya_diri(): void
    {
        $benar = config('kebodohan.soal')[0]['benar'];

        $this->withSession($this->sesi())->getJson('/tes/soal');
        $this->postJson('/tes/jawab', ['pilihan' => $benar, 'ganti' => 0])
            ->assertOk()
            ->assertJson(['benar' => true, 'turun' => 0, 'pd' => 100, 'selesai' => false]);
    }

    public function test_jawaban_salah_menurunkan_percaya_diri(): void
    {
        $salah = (config('kebodohan.soal')[0]['benar'] + 1) % 4;

        $this->withSession($this->sesi())->getJson('/tes/soal');
        $this->postJson('/tes/jawab', ['pilihan' => $salah])
            ->assertOk()
            ->assertJson(['benar' => false, 'turun' => config('kebodohan.penurunan_pd')[1]]);
    }

    public function test_jawab_tanpa_soal_aktif_ditolak(): void
    {
        $this->postJson('/tes/jawab', ['pilihan' => 0])->assertStatus(409);
    }

    public function test_tes_selesai_setelah_semua_soal_dijawab(): void
    {
        $this->withSession($this->sesi(2))->getJson('/tes/soal');
        $this->postJson('/tes/jawab', ['pilihan' => 0])->assertJson(['selesai' => false]);

        $this->getJson('/tes/soal');
        $this->postJson('/tes/jawab', ['pilihan' => 0])->assertJson(['selesai' => true]);

        $this->assertTrue(session('tes.selesai'));
        $this->assertCount(2, session('tes.catatan'));
    }

    public function test_hasil_tanpa_tes_selesai_diarahkan_ke_tes(): void
    {
        $this->get('/hasil')->assertRedirect('/tes');
    }
}
