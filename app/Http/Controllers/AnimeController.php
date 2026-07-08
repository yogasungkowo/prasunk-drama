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
            $anime = $this->findAnimeFallback($slug);
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

    public function unlimited(Request $request)
    {
        $animeList = [];
        $perPage = 25;
        $currentPage = max(1, (int) $request->input('page', 1));
        $filters = [
            'q' => trim((string) $request->input('q', '')),
            'sort' => (string) ($request->input('sort', 'newest') ?: 'newest'),
            'genre' => (string) ($request->input('genre', '') ?? ''),
            'status' => (string) ($request->input('status', '') ?? ''),
            'type' => (string) ($request->input('type', '') ?? ''),
        ];

        $genreOptions = $this->fetchAnimeGenreOptions();
        $statusFilter = $this->normalizedAnimeStatus($filters['status']);

        if ($filters['genre']) {
            $animeList = $this->fetchAnimeByGenre($filters['genre']);
        } elseif ($statusFilter === 'Ongoing') {
            $animeList = $this->fetchAnimeByStatus('ongoing');
            if (empty($animeList)) {
                $animeList = $this->fetchUnlimitedAnimeList();
            }
        } elseif ($statusFilter === 'Complete') {
            $animeList = $this->fetchAnimeByStatus('complete');
            if (empty($animeList)) {
                $animeList = $this->fetchUnlimitedAnimeList();
            }
        } else {
            $animeList = $this->fetchUnlimitedAnimeList();
        }

        $animeList = array_values(array_filter($animeList, fn ($anime) => is_array($anime)));
        $filterOptions = $this->buildUnlimitedFilterOptions($animeList, $genreOptions);
        $filteredAnime = $this->filterUnlimitedAnime($animeList, $filters);
        $pageItems = array_slice($filteredAnime, ($currentPage - 1) * $perPage, $perPage);
        $pageItems = $this->enrichAnimeDetails($pageItems);

        if (($filters['sort'] ?? '') === 'rating') {
            usort($pageItems, fn ($a, $b) => $this->animeRatingValue($b) <=> $this->animeRatingValue($a));
        }

        $paginatedAnime = new LengthAwarePaginator(
            $pageItems,
            count($filteredAnime),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->except('page'),
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('pages.anime.partials.unlimited-results', [
                    'animeList' => $paginatedAnime,
                ])->render(),
                'summary' => view('pages.anime.partials.unlimited-summary', [
                    'animeList' => $paginatedAnime,
                ])->render(),
                'total' => $paginatedAnime->total(),
            ]);
        }

        return view('pages.anime.unlimited', [
            'animeList' => $paginatedAnime,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
        ]);
    }

    private function fetchUnlimitedAnimeList(): array
    {
        $cacheKey = 'anime-unlimited-list:v2';
        $cached = Cache::get($cacheKey);

        if (!empty($cached)) {
            return $cached;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/unlimited");

            if ($response->successful()) {
                $animeList = $this->normalizeUnlimitedAnimeList($response->json()['data'] ?? []);

                if (!empty($animeList)) {
                    Cache::put($cacheKey, $animeList, now()->addHours(6));
                }

                return $animeList;
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    private function fetchAnimeByGenre(string $genreSlug): array
    {
        $cacheKey = "anime-genre-list:v2:{$genreSlug}";
        $cached = Cache::get($cacheKey);

        if (!empty($cached)) {
            return $cached;
        }

        $animeList = [];

        for ($page = 1; $page <= 30; $page++) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/anime/genre/{$genreSlug}", ['page' => $page]);

                if (!$response->successful()) {
                    break;
                }

                $items = $response->json()['data']['animeList'] ?? [];

                if (empty($items)) {
                    break;
                }

                foreach ($items as $anime) {
                    if (is_array($anime)) {
                        $anime['genreList'] = $anime['genreList'] ?? [[
                            'title' => str($genreSlug)->replace('-', ' ')->title()->toString(),
                            'genreId' => $genreSlug,
                        ]];
                        $animeList[] = $this->normalizeAnimeCard($anime);
                    }
                }

                if (count($items) < 10) {
                    break;
                }
            } catch (\Exception $e) {
                break;
            }
        }

        if (!empty($animeList)) {
            Cache::put($cacheKey, $animeList, now()->addHours(3));
        }

        return $animeList;
    }

    private function fetchAnimeByStatus(string $status): array
    {
        $status = $status === 'ongoing' ? 'ongoing' : 'complete';
        $cacheKey = "anime-status-list:v1:{$status}";
        $cached = Cache::get($cacheKey);

        if (!empty($cached)) {
            return $cached;
        }

        $endpoint = $status === 'ongoing' ? 'ongoing-anime' : 'complete-anime';
        $label = $status === 'ongoing' ? 'Ongoing' : 'Complete';
        $animeList = [];

        for ($page = 1; $page <= 80; $page++) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/anime/{$endpoint}", ['page' => $page]);

                if (!$response->successful()) {
                    break;
                }

                $items = $response->json()['data']['animeList'] ?? [];

                if (empty($items)) {
                    break;
                }

                foreach ($items as $anime) {
                    if (is_array($anime)) {
                        $anime['status'] = $label;
                        $animeList[] = $this->normalizeAnimeCard($anime);
                    }
                }

                if (count($items) < 10) {
                    break;
                }
            } catch (\Exception $e) {
                break;
            }
        }

        if (!empty($animeList)) {
            Cache::put($cacheKey, $animeList, now()->addHours(3));
        }

        return $animeList;
    }

    private function fetchAnimeGenreOptions(): array
    {
        $cacheKey = 'anime-genre-options:v2';
        $cached = Cache::get($cacheKey);

        if (!empty($cached)) {
            return $cached;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/genre");

            if (!$response->successful()) {
                return [];
            }

            $genres = $response->json()['data']['genreList'] ?? [];
            $options = [];

            foreach ($genres as $genre) {
                $title = is_string($genre) ? $genre : ($genre['title'] ?? $genre['name'] ?? $genre['genreName'] ?? '');
                $slug = is_string($genre)
                    ? \Illuminate\Support\Str::slug($genre)
                    : ($genre['genreId'] ?? $genre['slug'] ?? \Illuminate\Support\Str::slug($title));

                if ($title && $slug) {
                    $options[$slug] = $title;
                }
            }

            asort($options);

            if (!empty($options)) {
                Cache::put($cacheKey, $options, now()->addHours(6));
            }

            return $options;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function enrichAnimeDetails(array $animeList): array
    {
        return array_map(function ($anime) {
            $anime = $this->normalizeAnimeCard($anime);
            $animeId = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';

            if (!$animeId || (!empty($anime['poster']) && (!empty($anime['score']) || !empty($anime['rating'])))) {
                return $anime;
            }

            $detail = $this->fetchAnimeDetail($animeId);

            return $this->mergeAnimeCardDetail($anime, is_array($detail) ? $detail : []);
        }, $animeList);
    }

    private function fetchAnimeDetail(string $animeId): array
    {
        $cacheKey = "anime-card-detail:v2:{$animeId}";
        $cached = Cache::get($cacheKey);

        if (!empty($cached)) {
            return $cached;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/anime/anime/{$animeId}");

            if ($response->successful()) {
                $detail = $response->json()['data'] ?? $response->json();

                if (!empty($detail) && is_array($detail)) {
                    Cache::put($cacheKey, $detail, now()->addHours(12));
                    return $detail;
                }
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    private function findAnimeFallback(string $slug): ?array
    {
        $detail = $this->fetchAnimeDetail($slug);

        if (!empty($detail)) {
            return $detail;
        }

        foreach ($this->fetchUnlimitedAnimeList() as $anime) {
            $keys = [
                $anime['animeId'] ?? '',
                $anime['slug'] ?? '',
                $anime['id'] ?? '',
            ];

            if (in_array($slug, array_filter($keys), true)) {
                return $this->normalizeAnimeCard($anime);
            }
        }

        return null;
    }

    private function mergeAnimeCardDetail(array $anime, array $detail): array
    {
        foreach (['poster', 'cover', 'thumbnail', 'score', 'rating', 'status', 'type', 'episodes', 'duration', 'genreList', 'genre'] as $key) {
            if (empty($anime[$key]) && !empty($detail[$key])) {
                $anime[$key] = $detail[$key];
            }
        }

        if (empty($anime['poster'])) {
            $anime['poster'] = $detail['poster'] ?? $detail['cover'] ?? $detail['thumbnail'] ?? '';
        }

        return $this->normalizeAnimeCard($anime);
    }

    private function normalizeUnlimitedAnimeList(array $data): array
    {
        if (isset($data['animeList']) && is_array($data['animeList'])) {
            return array_map(fn ($anime) => $this->normalizeAnimeCard($anime), $data['animeList']);
        }

        if (isset($data['list']) && is_array($data['list'])) {
            $animeList = [];

            foreach ($data['list'] as $group) {
                $startWith = $group['startWith'] ?? '';

                foreach (($group['animeList'] ?? []) as $anime) {
                    if (!is_array($anime)) {
                        continue;
                    }

                    if ($startWith !== '') {
                        $anime['startWith'] = $startWith;
                    }

                    $animeList[] = $this->normalizeAnimeCard($anime);
                }
            }

            return $animeList;
        }

        return array_is_list($data)
            ? array_map(fn ($anime) => $this->normalizeAnimeCard($anime), $data)
            : [];
    }

    private function normalizeAnimeCard(array $anime): array
    {
        $anime['slug'] = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';
        $anime['animeId'] = $anime['animeId'] ?? $anime['slug'];
        $anime['type'] = $anime['type'] ?? 'Anime';

        if (empty($anime['status']) && !empty($anime['title'])) {
            $anime['status'] = str_contains(strtolower($anime['title']), 'on-going') ? 'Ongoing' : 'Complete';
        }

        return $anime;
    }

    private function buildUnlimitedFilterOptions(array $animeList, array $genreOptions = []): array
    {
        $genres = $genreOptions;
        $statuses = [
            'Ongoing' => 'Ongoing',
            'Complete' => 'Complete',
        ];
        $types = [];

        foreach ($animeList as $anime) {
            foreach ($this->extractGenreOptions($anime) as $genre) {
                $genres[$genre['slug']] = $genre['title'];
            }

            $status = trim((string) ($anime['status'] ?? ''));
            if ($status !== '') {
                $statuses[$status] = $status;
            }

            $type = trim((string) ($anime['type'] ?? ''));
            if ($type !== '') {
                $types[$type] = $type;
            }
        }

        asort($genres);
        natcasesort($statuses);
        natcasesort($types);

        return [
            'genres' => $genres,
            'statuses' => array_values($statuses),
            'types' => array_values($types),
        ];
    }

    private function filterUnlimitedAnime(array $animeList, array $filters): array
    {
        $filtered = array_values(array_filter($animeList, function ($anime) use ($filters) {
            if ($filters['q'] !== '') {
                $keyword = strtolower($filters['q']);
                $haystack = strtolower(implode(' ', array_filter([
                    $anime['title'] ?? '',
                    $anime['japanese'] ?? '',
                    $anime['synopsis'] ?? '',
                    $anime['description'] ?? '',
                ], 'is_string')));

                if (!str_contains($haystack, $keyword)) {
                    return false;
                }
            }

            if ($filters['genre'] !== '') {
                $genreSlugs = array_column($this->extractGenreOptions($anime), 'slug');
                if (!in_array($filters['genre'], $genreSlugs, true)) {
                    return false;
                }
            }

            if ($filters['status'] !== '' && $this->normalizedAnimeStatus((string) ($anime['status'] ?? '')) !== $this->normalizedAnimeStatus($filters['status'])) {
                return false;
            }

            if ($filters['type'] !== '' && strcasecmp((string) ($anime['type'] ?? ''), $filters['type']) !== 0) {
                return false;
            }

            return true;
        }));

        $sort = $filters['sort'] ?? 'newest';

        if ($sort === 'az') {
            usort($filtered, fn ($a, $b) => strcasecmp($a['title'] ?? '', $b['title'] ?? ''));
        } elseif ($sort === 'za') {
            usort($filtered, fn ($a, $b) => strcasecmp($b['title'] ?? '', $a['title'] ?? ''));
        } elseif ($sort === 'oldest') {
            $filtered = array_reverse($filtered);
        } elseif ($sort === 'rating') {
            usort($filtered, fn ($a, $b) => $this->animeRatingValue($b) <=> $this->animeRatingValue($a));
        }

        return $filtered;
    }

    private function extractGenreOptions(array $anime): array
    {
        $genres = $anime['genreList'] ?? $anime['genres'] ?? $anime['genre'] ?? [];

        if (is_string($genres)) {
            $genres = array_map('trim', explode(',', $genres));
        }

        if (!is_array($genres)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($genre) {
            $title = is_string($genre)
                ? $genre
                : ($genre['title'] ?? $genre['name'] ?? $genre['genreName'] ?? '');
            $slug = is_string($genre)
                ? \Illuminate\Support\Str::slug($genre)
                : ($genre['genreId'] ?? $genre['slug'] ?? \Illuminate\Support\Str::slug($title));

            if (!$title || !$slug) {
                return null;
            }

            return [
                'title' => $title,
                'slug' => $slug,
            ];
        }, $genres)));
    }

    private function animeRatingValue(array $anime): float
    {
        $rating = $anime['score'] ?? $anime['rating'] ?? 0;

        if (is_string($rating)) {
            $rating = preg_replace('/[^0-9.]/', '', $rating);
        }

        return (float) $rating;
    }

    private function normalizedAnimeStatus(string $status): string
    {
        $status = strtolower(trim($status));
        $status = str_replace(['-', '_'], ' ', $status);
        $status = preg_replace('/\s+/', ' ', $status) ?? $status;

        if (in_array($status, ['ongoing', 'on going', 'on air', 'airing'], true)) {
            return 'Ongoing';
        }

        if (in_array($status, ['complete', 'completed', 'tamat', 'finished'], true)) {
            return 'Complete';
        }

        return $status ? str($status)->title()->toString() : '';
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
