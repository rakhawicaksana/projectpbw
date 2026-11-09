<?php

use App\Http\Controllers\SkorController;
use App\Http\Controllers\TesController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'tema.arcade')->name('beranda');

// Quiz: mulai -> ambil soal -> jawab -> (ulangi) -> hasil
Route::get('/tes', [TesController::class, 'index'])->name('tes');
Route::get('/tes/soal', [TesController::class, 'soal'])->name('tes.soal');
Route::post('/tes/jawab', [TesController::class, 'jawab'])->middleware('throttle:60,1')->name('tes.jawab');
Route::get('/hasil', [TesController::class, 'hasil'])->name('hasil');

// Leaderboard (baca saja, data dari seeder)
Route::get('/papan', [SkorController::class, 'papan'])->name('papan');
