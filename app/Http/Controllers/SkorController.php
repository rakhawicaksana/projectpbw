<?php

namespace App\Http\Controllers;

use App\Models\Skor;
use Illuminate\Contracts\View\View;

class SkorController extends Controller
{
    /** Leaderboard: 10 skor tertinggi, seri diurutkan waktu tercepat. Variabel view: $daftar. */
    public function papan(): View
    {
        $daftar = Skor::orderByDesc('persen')
            ->orderBy('waktu')
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        return view('papan', ['daftar' => $daftar]);
    }
}
