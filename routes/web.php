<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route halaman utama dan proses pencarian berita TF-IDF
|
*/

Route::get('/', [SearchController::class, 'index']);

Route::get('/search', [SearchController::class, 'search'])
    ->name('search');