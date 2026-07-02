<?php

use App\Http\Controllers\DramaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DramaController::class, 'index']);
Route::get('/drama/detail', [DramaController::class, 'detail'])->name('drama.detail');
Route::get('/drama/suggest', [DramaController::class, 'searchSuggest'])->name('drama.suggest');
Route::get('/drama/play', [DramaController::class, 'play'])->name('drama.play');
Route::get('/drama/hls', [DramaController::class, 'proxyHls'])->name('drama.hls');
