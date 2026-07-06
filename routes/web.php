<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\DramaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DramaController::class, 'index']);
Route::get('/drama/detail', [DramaController::class, 'detail'])->name('drama.detail');
Route::get('/drama/suggest', [DramaController::class, 'searchSuggest'])->name('drama.suggest');
Route::get('/drama/play', [DramaController::class, 'play'])->name('drama.play');
Route::get('/drama/hls', [DramaController::class, 'proxyHls'])->name('drama.hls');
Route::get('/drama/segment', [DramaController::class, 'proxySegment'])->name('drama.segment');

// Proxies for Melolo Client-Side SDK
Route::get('/api/melolo/episode', [DramaController::class, 'meloloEpisodeProxy']);
Route::get('/api/melolo/key', [DramaController::class, 'meloloKeyProxy']);

// Anime Routes
Route::prefix('anime')->name('anime.')->group(function () {
    Route::get('/', [AnimeController::class, 'index'])->name('index');
    Route::get('/schedule', [AnimeController::class, 'schedule'])->name('schedule');
    Route::get('/ongoing', [AnimeController::class, 'ongoing'])->name('ongoing');
    Route::get('/ongoing-ajax', [AnimeController::class, 'ongoingAjax'])->name('ongoing.ajax');
    Route::get('/complete', [AnimeController::class, 'complete'])->name('complete');
    Route::get('/genre', [AnimeController::class, 'genre'])->name('genre');
    Route::get('/genre/{slug}', [AnimeController::class, 'genreList'])->name('genre.list');
    Route::get('/search-ajax', [AnimeController::class, 'searchAjax'])->name('search.ajax');
    Route::get('/search/{keyword}', [AnimeController::class, 'search'])->name('search');
    Route::get('/anime/{slug}', [AnimeController::class, 'detail'])->name('detail');
    Route::get('/episode/{slug}', [AnimeController::class, 'episode'])->name('episode');
    Route::get('/batch/{slug}', [AnimeController::class, 'batch'])->name('batch');
    Route::get('/unlimited', [AnimeController::class, 'unlimited'])->name('unlimited');
    Route::get('/server/{serverId}', [AnimeController::class, 'server'])->name('server');
});
