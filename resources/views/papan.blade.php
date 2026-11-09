@extends('layout.app')

@section('judul', 'Leaderboard - ' . config('kebodohan.nama'))

@section('isi')
<section class="tengah">
    <h1 class="judul">LEADERBOARD</h1>
    <p class="sub">10 SKOR TERTINGGI. SERI? YANG LEBIH CEPAT MENANG.</p>

    {{-- Komponen daftar milik D. Variabel: $daftar --}}
    @include('partials.papan', ['daftar' => $daftar])

    <p style="margin-top:40px"><a class="tombol" href="{{ route('tes') }}">▶ UJI OTAK LAGI</a></p>
</section>
@endsection
