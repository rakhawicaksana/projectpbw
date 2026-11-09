<?php

namespace Tests\Feature;

use App\Models\Skor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkorTest extends TestCase
{
    use RefreshDatabase;

    public function test_kode_dibuat_otomatis_dan_unik(): void
    {
        $a = Skor::factory()->create();
        $b = Skor::factory()->create();

        $this->assertSame(8, strlen($a->kode));
        $this->assertNotSame($a->kode, $b->kode);
    }

    public function test_papan_kosong_menampilkan_pesan(): void
    {
        $this->get('/papan')->assertOk()->assertSee('Belum ada pemain');
    }

    public function test_papan_hanya_10_teratas_dan_urut_skor(): void
    {
        foreach (range(1, 12) as $i) {
            Skor::factory()->create(['inisial' => 'AB' . chr(64 + $i), 'persen' => $i * 8]);
        }

        $respons = $this->get('/papan')->assertOk();

        $respons->assertSeeInOrder(['ABL', 'ABK', 'ABJ']);   // 96%, 88%, 80%
        $respons->assertDontSee('ABA');                      // 8%, di luar 10 besar
    }

    public function test_seri_diurutkan_waktu_tercepat(): void
    {
        Skor::factory()->create(['inisial' => 'LMB', 'persen' => 70, 'waktu' => 200]);
        Skor::factory()->create(['inisial' => 'CPT', 'persen' => 70, 'waktu' => 60]);

        $this->get('/papan')->assertSeeInOrder(['CPT', 'LMB']);
    }
}
