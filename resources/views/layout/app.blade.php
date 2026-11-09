<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', config('kebodohan.nama'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/arcade.css') }}">
    @stack('styles')
</head>
<body>
    <a class="lewati" href="#isi">Lewati ke konten</a>

    <nav class="nav">
        <a href="{{ route('beranda') }}">▶ {{ strtoupper(config('kebodohan.nama')) }}</a>
        <div class="nav__menu">
            <a href="{{ route('tes') }}" @class(['aktif' => request()->routeIs('tes', 'hasil')])>[ TES ]</a>
            <a href="{{ route('papan') }}" @class(['aktif' => request()->routeIs('papan')])>[ LEADERBOARD ]</a>
        </div>
    </nav>

    <main id="isi">
        @yield('isi')
    </main>

    @stack('scripts')
</body>
</html>
