@extends('layout.app')

{{-- Variabel dari TesController@hasil:
     $persen (kecerdasan), $ketelitian, $kecepatan, $percaya_diri, $waktu (detik), $benar, $total,
     $kategori (kunci) dan $info (nama, deskripsi, penutup, sprite) --}}
@section('judul', 'Hasil Analisis - ' . config('kebodohan.nama'))

@push('styles')
<style>
.hasil__persen{font-size:clamp(4.6rem,20vw,8.6rem);color:var(--kuning);text-shadow:6px 6px 0 var(--pink);margin:14px 0 0}
.hasil__kat{display:flex;align-items:center;justify-content:center;gap:18px;margin-top:10px;text-align:left}
.hasil__sprite{width:84px;height:84px;image-rendering:pixelated;flex:none}
.hasil__nama{font-family:var(--judul);font-weight:800;font-size:clamp(1.4rem,5vw,2.4rem);text-transform:uppercase}
.hasil__ket{color:var(--redup);margin-top:4px}
.hasil__quote{font-style:italic;color:var(--kuning);margin-top:16px}
.statistik{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:34px auto 0;max-width:900px}
.statistik div{border:3px solid var(--ungu);background:var(--panel);padding:16px 6px}
.statistik b{display:block;font-family:var(--judul);font-weight:800;font-size:clamp(1.6rem,5vw,2.6rem)}
.statistik small{letter-spacing:1px;font-weight:700;color:var(--redup);font-size:.75rem}
.rincian{margin:10px auto 0;color:var(--redup);font-size:.95rem}
@media (max-width:600px){.statistik{grid-template-columns:1fr 1fr}.hasil__sprite{width:64px;height:64px}}
</style>
@endpush

@section('isi')
<section class="tengah">
    <p class="hs">— HASIL ANALISIS —</p>

    <h1 class="judul hasil__persen">{{ $persen }}%</h1>
    <p class="sub">KECERDASAN • {{ $benar }} DARI {{ $total }} SOAL BENAR</p>

    <div class="hasil__kat">
        <img class="hasil__sprite" src="{{ asset('img/sprite-' . $info['sprite'] . '.svg') }}" alt="" width="84" height="84">
        <div>
            <div class="hasil__nama">{{ $info['nama'] }}</div>
            <div class="hasil__ket">{{ $info['deskripsi'] }}</div>
        </div>
    </div>
    <p class="hasil__quote">"{{ $info['penutup'] }}"</p>

    {{-- PPT slide 13: tujuan akhir pengguna. Kepercayaan diri bisa lebih dari 100%. --}}
    <div class="statistik">
        <div><b class="pk">{{ $persen }}%</b><small>KECERDASAN</small></div>
        <div><b class="yl">{{ $ketelitian }}%</b><small>KETELITIAN</small></div>
        <div><b class="pn">{{ $percaya_diri }}%</b><small>KEPERCAYAAN DIRI</small></div>
        <div><b style="color:#3b82f6">…</b><small>MEMBACA SOAL</small></div>
    </div>
    <p class="rincian">Kecepatan {{ $kecepatan }}% • Waktu total {{ sprintf('%02d:%02d', intdiv($waktu, 60), $waktu % 60) }}</p>

    <p style="margin-top:34px;display:flex;flex-wrap:wrap;gap:18px;justify-content:center">
        <a class="tombol" href="{{ route('tes') }}">▶ ULANGI TES</a>
        <a class="tombol tombol--putih" href="{{ route('papan') }}">LEADERBOARD ▶</a>
    </p>

    <p class="sub" style="margin-top:30px">"Terima kasih sudah ikut tes. Jangan terlalu dipikirkan."</p>
</section>
@endsection
