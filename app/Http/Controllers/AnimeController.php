<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
                $ongoingAnime = $this->enrichAnimeScores($ongoingAnime);
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
        $relatedAnime = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/anime/{$slug}");

            if ($response->successful()) {
                $anime = $response->json()['data'] ?? $response->json();
            }

            if ($anime) {
                $relatedAnime = $this->getRelatedAnimeByGenre($anime['genreList'] ?? $anime['genre'] ?? [], [
                    $slug,
                    $anime['animeId'] ?? '',
                    $anime['title'] ?? '',
                ]);
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$anime) {
            return redirect('/anime')->with('error', 'Anime not found');
        }

        return view('pages.anime.detail', [
            'anime' => $anime,
            'relatedAnime' => $relatedAnime,
            'slug' => $slug,
        ]);
    }

    public function episode($slug)
    {
        $episode = null;
        $batchDownload = null;
        $animeDetail = null;
        $relatedAnime = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/episode/{$slug}");

            if ($response->successful()) {
                $episode = $response->json()['data'] ?? $response->json();
            }

            if ($episode) {
                $batchInfo = $episode['batch'] ?? $episode['info']['batch'] ?? null;
                $batchSlug = is_array($batchInfo)
                    ? ($batchInfo['batchId'] ?? $batchInfo['slug'] ?? '')
                    : (is_string($batchInfo) ? $batchInfo : '');

                if ((!$batchSlug || empty($episode['info']['genreList'])) && !empty($episode['animeId'])) {
                    $detailResponse = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0'
                    ])->get("{$this->baseUrl}/anime/anime/{$episode['animeId']}");

                    if ($detailResponse->successful()) {
                        $animeDetail = $detailResponse->json()['data'] ?? $detailResponse->json();
                        $batchInfo = $animeDetail['batch'] ?? null;
                        $detailBatchSlug = is_array($batchInfo)
                            ? ($batchInfo['batchId'] ?? $batchInfo['slug'] ?? '')
                            : (is_string($batchInfo) ? $batchInfo : '');

                        if ($detailBatchSlug) {
                            $batchSlug = $detailBatchSlug;
                        }
                    }
                }

                if ($batchSlug) {
                    $batchResponse = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0'
                    ])->get("{$this->baseUrl}/anime/batch/{$batchSlug}");

                    if ($batchResponse->successful()) {
                        $batchDownload = $batchResponse->json()['data'] ?? $batchResponse->json();
                    }
                }

                $relatedGenres = $episode['info']['genreList']
                    ?? $episode['genreList']
                    ?? $animeDetail['genreList']
                    ?? [];
                $relatedAnime = $this->getRelatedAnimeByGenre($relatedGenres, [
                    $episode['animeId'] ?? '',
                    $animeDetail['title'] ?? '',
                ]);
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$episode) {
            return redirect('/anime')->with('error', 'Episode not found');
        }

        return view('pages.anime.play', [
            'episode' => $episode,
            'batchDownload' => $batchDownload,
            'relatedAnime' => $relatedAnime,
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
                $animeList = $this->enrichAnimeScores($animeList);
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
                $animeList = $this->enrichAnimeScores($animeList);
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
        $relatedAnime = null;

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/batch/{$slug}");

            if ($response->successful()) {
                $batch = $response->json()['data'] ?? $response->json();
            }

            if ($batch) {
                $relatedAnime = $this->getRelatedAnimeByGenre($batch['genreList'] ?? $batch['genres'] ?? $batch['genre'] ?? [], [
                    $slug,
                    $batch['animeId'] ?? '',
                    $batch['title'] ?? '',
                ]);
            }
        } catch (\Exception $e) {
            // Silence
        }

        if (!$batch) {
            return redirect('/anime')->with('error', 'Batch not found');
        }

        return view('pages.anime.batch', [
            'batch' => $batch,
            'relatedAnime' => $relatedAnime,
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

    private function getRelatedAnimeByGenre($genres, array $exclude = [], int $limit = 10): ?array
    {
        $genre = $this->firstGenre($genres);

        if (!$genre || empty($genre['slug'])) {
            return null;
        }

        $animeList = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/genre/{$genre['slug']}", ['page' => 1]);

            if ($response->successful()) {
                $animeList = $response->json()['data']['animeList'] ?? [];
            }
        } catch (\Exception $e) {
            return null;
        }

        $exclude = array_filter(array_map(fn ($value) => strtolower((string) $value), $exclude));
        $animeList = array_values(array_filter($animeList, function ($anime) use ($exclude) {
            $keys = [
                $anime['animeId'] ?? '',
                $anime['slug'] ?? '',
                $anime['id'] ?? '',
                $anime['title'] ?? '',
            ];

            foreach ($keys as $key) {
                if ($key !== '' && in_array(strtolower((string) $key), $exclude, true)) {
                    return false;
                }
            }

            return true;
        }));

        if (empty($animeList)) {
            return null;
        }

        return [
            'genreTitle' => $genre['title'],
            'genreSlug' => $genre['slug'],
            'animeList' => array_slice($animeList, 0, $limit),
        ];
    }

    private function enrichAnimeScores(array $animeList): array
    {
        return array_map(function ($anime) {
            if (!empty($anime['score']) || !empty($anime['rating'])) {
                return $anime;
            }

            $animeId = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';

            if (!$animeId) {
                return $anime;
            }

            $score = Cache::remember("anime-score:{$animeId}", now()->addHours(6), function () use ($animeId) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0'
                    ])->get("{$this->baseUrl}/anime/anime/{$animeId}");

                    if ($response->successful()) {
                        $detail = $response->json()['data'] ?? $response->json();
                        return $detail['score'] ?? $detail['rating'] ?? null;
                    }
                } catch (\Exception $e) {
                    return null;
                }

                return null;
            });

            if ($score) {
                $anime['score'] = $score;
            }

            return $anime;
        }, $animeList);
    }

    private function firstGenre($genres): ?array
    {
        if (!is_array($genres) || empty($genres)) {
            return null;
        }

        $genre = reset($genres);
        $title = is_string($genre)
            ? $genre
            : ($genre['title'] ?? $genre['name'] ?? $genre['genreName'] ?? '');
        $slug = is_string($genre)
            ? \Illuminate\Support\Str::slug($genre)
            : ($genre['genreId'] ?? $genre['slug'] ?? \Illuminate\Support\Str::slug($title));

        if (!$title && $slug) {
            $title = str($slug)->replace('-', ' ')->title()->toString();
        }

        if (!$title || !$slug) {
            return null;
        }

        return [
            'title' => $title,
            'slug' => $slug,
        ];
    }
}
