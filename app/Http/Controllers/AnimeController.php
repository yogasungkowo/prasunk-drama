<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class AnimeController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('ANIME_BASE_URL');
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $homeData = [];
        $ongoingAnime = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/home");

            if ($response->successful()) {
                $homeData = $response->json()['data'] ?? [];
            }

            $ongoingResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/ongoing-anime", ['page' => $page]);

            if ($ongoingResponse->successful()) {
                $ongoingAnime = $ongoingResponse->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.index', [
            'homeData' => $homeData,
            'ongoingAnime' => $ongoingAnime,
            'currentPage' => (int) $page,
        ]);
    }

    public function schedule()
    {
        $schedule = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/schedule");

            if ($response->successful()) {
                $schedule = $response->json()['data'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.schedule', [
            'schedule' => $schedule,
        ]);
    }

    public function detail($slug)
    {
        $anime = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/anime/{$slug}");

            if ($response->successful()) {
                $anime = $response->json()['data'] ?? $response->json();
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$anime) {
            return redirect('/anime')->with('error', 'Anime not found');
        }

        return view('pages.anime.detail', [
            'anime' => $anime,
            'slug' => $slug,
        ]);
    }

    public function episode($slug)
    {
        $episode = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/episode/{$slug}");

            if ($response->successful()) {
                $episode = $response->json()['data'] ?? $response->json();
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$episode) {
            return redirect('/anime')->with('error', 'Episode not found');
        }

        return view('pages.anime.play', [
            'episode' => $episode,
            'slug' => $slug,
        ]);
    }

    public function ongoing(Request $request)
    {
        $page = $request->input('page', 1);
        $animeList = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/ongoing-anime", ['page' => $page]);

            if ($response->successful()) {
                $animeList = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.ongoing', [
            'animeList' => $animeList,
            'currentPage' => (int) $page,
        ]);
    }

    public function ongoingAjax(Request $request)
    {
        $page = $request->input('page', 1);
        $animeList = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/ongoing-anime", ['page' => $page]);

            if ($response->successful()) {
                $animeList = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return response()->json([
            'data' => $animeList,
            'currentPage' => (int) $page,
        ]);
    }

    public function complete(Request $request)
    {
        $page = $request->input('page', 1);
        $animeList = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/complete-anime", ['page' => $page]);

            if ($response->successful()) {
                $animeList = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.complete', [
            'animeList' => $animeList,
            'currentPage' => (int) $page,
        ]);
    }

    public function genre()
    {
        $genres = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/genre");

            if ($response->successful()) {
                $genres = $response->json()['data']['genreList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.genre', [
            'genres' => $genres,
        ]);
    }

    public function genreList($slug, Request $request)
    {
        $page = $request->input('page', 1);
        $animeList = [];
        $genreName = str_replace('-', ' ', $slug);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/genre/{$slug}", ['page' => $page]);

            if ($response->successful()) {
                $animeList = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.genre-list', [
            'animeList' => $animeList,
            'genreName' => ucfirst($genreName),
            'slug' => $slug,
            'currentPage' => (int) $page,
        ]);
    }

    public function search($keyword, Request $request)
    {
        $results = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/search/{$keyword}");

            if ($response->successful()) {
                $results = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.search', [
            'results' => $results,
            'keyword' => $keyword,
        ]);
    }

    public function searchAjax(Request $request)
    {
        $keyword = $request->input('q', '');

        if (!$keyword) {
            return response()->json([]);
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/search/{$keyword}");

            if ($response->successful()) {
                $data = $response->json()['data']['animeList'] ?? [];
                return response()->json(array_slice($data, 0, 5));
            }
        } catch (\Exception $e) {
            // Silence
        }

        return response()->json([]);
    }

    public function batch($slug)
    {
        $batch = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/batch/{$slug}");

            if ($response->successful()) {
                $batch = $response->json()['data'] ?? $response->json();
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$batch) {
            return redirect('/anime')->with('error', 'Batch not found');
        }

        return view('pages.anime.batch', [
            'batch' => $batch,
            'slug' => $slug,
        ]);
    }

    public function server($serverId)
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/server/{$serverId}");

            if ($response->successful()) {
                $data = $response->json()['data'] ?? $response->json();
                return response()->json($data);
            }
        } catch (\Exception $e) {
            // Silence
        }

        return response()->json(['error' => 'Failed to fetch server URL'], 500);
    }

    public function unlimited()
    {
        $animeList = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/unlimited");

            if ($response->successful()) {
                $animeList = $response->json()['data'] ?? [];
            }
        } catch (\Exception $e) {
            // Silence
        }

        return view('pages.anime.unlimited', [
            'animeList' => $animeList,
        ]);
    }
}
