@extends('layouts.app')

@section('judul', config('kebodohan.nama') . ' - Quiz Interaktif')

@section('isi')
<section class="tengah">
    {{-- Semua teks di bawah mengikuti PPT Kelompok 6. Tidak ada daftar peringkat di beranda. --}}
    <p class="hs">QUIZ INTERAKTIF</p>

    <h1 class="judul judul--besar" style="font-size:clamp(2.6rem,10vw,5.6rem)">PENGUKUR<br>KEBODOHAN</h1>
    <p class="sub">Karena IQ tinggi belum tentu selamat dari pertanyaan sederhana.</p>

    <p style="margin-top:22px"><span class="lencana">PROYEK YANG SANGAT ILMIAH*</span></p>
    <p class="pk" style="margin-top:14px;letter-spacing:3px">LOGIKA × KETELITIAN × JEBAKAN</p>

    <div class="hp">
        <p class="pk">KEPERCAYAAN DIRI KAMU SEKARANG</p>
        <div class="bar" aria-hidden="true">@for ($i = 0; $i < 10; $i++)<i></i>@endfor</div>
    </div>

    <p style="margin-top:46px"><a class="tombol kedip" href="{{ route('tes') }}">▶ MULAI UJI OTAK</a></p>
    <p class="kutipan">"Tidak benar-benar mengukur kebodohan... tetapi cukup membuat kita mempertanyakan keputusan hidup."</p>

    {{-- PPT slide 5: konsep utama --}}
    <div class="tahap" aria-label="Lima tahap tes">
        <div><b>1. MASUK</b><small>masih tenang</small></div>
        <div><b>2. DIUJI</b><small>mulai fokus</small></div>
        <div><b>3. DIJEBAK</b><small>mulai curiga</small></div>
        <div><b>4. DINILAI</b><small>keringat dingin</small></div>
        <div><b>5. DIHAKIMI</b><small>secara ringan</small></div>
    </div>
    <p class="slogan">ANDA TIDAK BODOH. ANDA CUMA TERLALU PERCAYA DIRI.</p>

    {{-- PPT slide 8: tingkat kesulitan --}}
    <div class="level3">
        @foreach (config('kebodohan.tingkat') as $no => $t)
            <div style="background:{{ ['', 'var(--hijau)', 'var(--kuning)', 'var(--pink)'][$no] }}">
                <small>LEVEL {{ $no }}</small><b>{{ $t['nama'] }}</b>{{ $t['ket'] }}
            </div>
        @endforeach
    </div>

    <p class="catatan-kaki">*hasil tes tidak dapat digunakan untuk melamar pekerjaan</p>

    <span class="dekor" style="left:0;top:300px" aria-hidden="true">👾</span>
    <span class="dekor" style="right:0;top:420px" aria-hidden="true">🧠</span>
</section>
@endsection
