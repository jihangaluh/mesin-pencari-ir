<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

// Jalur untuk menampilkan halaman awal (kolom pencarian)
Route::get('/', [SearchController::class, 'index']);

// Jalur untuk memproses hasil pencarian saat tombol "Cari" ditekan
Route::get('/search', [SearchController::class, 'search'])->name('search');