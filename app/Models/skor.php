<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Skor extends Model
{
    use HasFactory;

    protected $table = 'skor';

    protected $fillable = [
        'kode', 'inisial', 'persen', 'ketelitian', 'kecepatan',
        'percaya_diri', 'waktu', 'kategori',
    ];

    protected $casts = [
        'persen'       => 'integer',
        'ketelitian'   => 'integer',
        'kecepatan'    => 'integer',
        'percaya_diri' => 'integer',
        'waktu'        => 'integer',
    ];

    protected static function booted(): void
    {
        // kode dibuat otomatis kalau belum diisi
        static::creating(function (Skor $skor) {
            if (empty($skor->kode)) {
                $skor->kode = static::buatKode();
            }
        });
    }

    /** Kode 8 karakter huruf besar/angka, dijamin belum dipakai. */
    public static function buatKode(): string
    {
        do {
            $kode = Str::upper(Str::random(8));
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    /** Info kategori dari config/kebodohan.php (milik B): nama, deskripsi, penutup, sprite. */
    public function getInfoKategoriAttribute(): array
    {
        $semua = config('kebodohan.kategori');

        return $semua[$this->kategori] ?? reset($semua);
    }

    /** Nama file sprite, mis. "siput" -> public/img/sprite-siput.svg */
    public function getSpriteAttribute(): string
    {
        return $this->info_kategori['sprite'];
    }

    /** Waktu total dalam format mm:ss, mis. "02:05" */
    public function getWaktuFormatAttribute(): string
    {
        return sprintf('%02d:%02d', intdiv($this->waktu, 60), $this->waktu % 60);
    }
}
