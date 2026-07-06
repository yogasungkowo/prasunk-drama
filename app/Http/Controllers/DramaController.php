<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class DramaController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('ANICHIN_API_KEY');
        $this->baseUrl = env('ANICHIN_BASE_URL');
    }

    private $sources = [
        'dramabox' => 'DramaBox',
        'melolo' => 'Melolo',
        'pinedrama' => 'PineDrama',
        'netshort' => 'NetShort',
        'shortmax' => 'ShortMax',
        'flickreels' => 'FlickReels',
        'reelshort' => 'ReelShort',
        'goodshort' => 'GoodShort',
        'dramabite' => 'DramaBite',
        'idrama' => 'iDrama',
        'starshort' => 'StarShort',
        'flareflow' => 'FlareFlow',
        'moboreels' => 'MoboReels',
        'dramanova' => 'DramaNova',
        'dramawave' => 'DramaWave'
    ];

    public function index(Request $request)
    {
        $selectedSource = $request->input('source', 'dramabox');
        $searchQuery = $request->input('search');

        if (!array_key_exists($selectedSource, $this->sources)) {
            $selectedSource = 'dramabox';   
        }

        $dramas = [];
        $trendingDramas = [];

        try {
            $params = [];
            if ($selectedSource === 'reelshort' || $selectedSource === 'dramabox' || $selectedSource === 'melolo') {
                $params['lang'] = 'id';
            }

            // Always fetch trending data for the Swiper carousel
            $trendingResponse = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$selectedSource}/trending", $params);

            if ($trendingResponse->successful()) {
                $data = $trendingResponse->json();
                $items = $data['items'] ?? [];

                foreach ($items as $index => $item) {
                    $formatted = [
                        'id' => $item['id'] ?? $item['dramaId'] ?? '',
                        'title' => $item['title'] ?? 'Unknown Title',
                        'description' => $item['description'] ?? $item['synopsis'] ?? 'No synopsis available.',
                        'cover' => $item['cover'] ?? $item['posterImg'] ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop',
                        'episodes' => $item['episodes'] ?? $item['totalEpisodes'] ?? 0,
                        'rating' => isset($item['rating']) ? number_format($item['rating'], 1) : number_format(8.0 + (hexdec(substr(md5($item['title'] ?? 'rating'), 0, 1)) / 7.5), 1),
                        'source' => $selectedSource,
                        'source_name' => $this->sources[$selectedSource] ?? ucfirst($selectedSource)
                    ];
                    $dramas[] = $formatted;
                    if ($index < 10) {
                        $trendingDramas[] = $formatted;
                    }
                }
            }

            // If search query, fetch search results (overrides main list)
            if ($searchQuery) {
                $searchParams = [];
                if ($selectedSource === 'dramabox') {
                    $searchParams['q'] = $searchQuery;
                    $searchParams['lang'] = 'id';
                } else {
                    $searchParams['query'] = $searchQuery;
                    if ($selectedSource === 'reelshort') {
                        $searchParams['lang'] = 'id';
                    }
                }
                $searchResponse = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/{$selectedSource}/search", $searchParams);

                if ($searchResponse->successful()) {
                    $data = $searchResponse->json();
                    $items = $data['items'] ?? [];
                    $dramas = [];

                    foreach ($items as $item) {
                        $dramas[] = [
                            'id' => $item['id'] ?? $item['dramaId'] ?? '',
                            'title' => $item['title'] ?? 'Unknown Title',
                            'description' => $item['description'] ?? $item['synopsis'] ?? 'No synopsis available.',
                            'cover' => $item['cover'] ?? $item['posterImg'] ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop',
                            'episodes' => $item['episodes'] ?? $item['totalEpisodes'] ?? 0,
                            'rating' => isset($item['rating']) ? number_format($item['rating'], 1) : number_format(8.0 + (hexdec(substr(md5($item['title'] ?? 'rating'), 0, 1)) / 7.5), 1),
                            'source' => $selectedSource,
                            'source_name' => $this->sources[$selectedSource] ?? ucfirst($selectedSource)
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silence errors
        }

        $allDramasCollection = collect($dramas);
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $currentItems = $allDramasCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedDramas = new LengthAwarePaginator(
            $currentItems,
            $allDramasCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pages.app.index', [
            'dramas' => $paginatedDramas,
            'trendingDramas' => $trendingDramas,
            'platforms' => $this->sources,
            'selectedSource' => $selectedSource,
            'searchQuery' => $searchQuery
        ]);
    }

    public function searchSuggest(Request $request)
    {
        $selectedSource = $request->input('source', 'dramabox');
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        try {
            $params = [];
            if ($selectedSource === 'dramabox') {
                $params['q'] = $query;
                $params['lang'] = 'id';
            } else {
                $params['query'] = $query;
                if ($selectedSource === 'reelshort') {
                    $params['lang'] = 'id';
                }
            }
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$selectedSource}/search", $params);

            if ($response->successful()) {
                $data = $response->json();
                $items = $data['items'] ?? [];
                $suggestions = [];
                $itemsSlice = array_slice($items, 0, 5);

                $needsDetails = false;
                foreach ($itemsSlice as $item) {
                    if (!isset($item['description']) && !isset($item['synopsis'])) {
                        $needsDetails = true;
                        break;
                    }
                }

                $details = [];
                if ($needsDetails) {
                    try {
                        $responses = Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($itemsSlice, $selectedSource) {
                            foreach ($itemsSlice as $item) {
                                $id = $item['id'] ?? $item['dramaId'] ?? '';
                                if ($id) {
                                    $params = ['id' => $id];
                                    if ($selectedSource === 'reelshort' || $selectedSource === 'dramabox') {
                                        $params['lang'] = 'id';
                                    }
                                    $pool->as($id)->withHeaders([
                                        'X-API-Key' => $this->apiKey,
                                        'User-Agent' => 'Mozilla/5.0'
                                    ])->get("{$this->baseUrl}/{$selectedSource}/detail", $params);
                                }
                            }
                        });

                        foreach ($responses as $id => $res) {
                            if ($res->successful()) {
                                $details[$id] = $res->json();
                            }
                        }
                    } catch (\Exception $poolEx) {
                        // Silence pool errors, fallback to empty details
                    }
                }

                foreach ($itemsSlice as $item) {
                    $id = $item['id'] ?? $item['dramaId'] ?? '';
                    $itemDetail = $details[$id] ?? null;

                    $desc = $item['description'] 
                        ?? $item['synopsis'] 
                        ?? $itemDetail['description'] 
                        ?? $itemDetail['synopsis'] 
                        ?? $itemDetail['data']['description'] 
                        ?? $itemDetail['data']['synopsis'] 
                        ?? 'No synopsis.';

                    $suggestions[] = [
                        'id' => $id,
                        'title' => $item['title'] ?? 'Unknown Title',
                        'description' => \Illuminate\Support\Str::limit($desc, 90),
                        'cover' => $item['cover'] ?? $item['posterImg'] ?? $itemDetail['cover'] ?? $itemDetail['data']['cover'] ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop',
                        'rating' => isset($item['rating']) ? number_format($item['rating'], 1) : number_format(8.0 + (hexdec(substr(md5($item['title'] ?? 'rating'), 0, 1)) / 7.5), 1),
                        'source' => $selectedSource
                    ];
                }

                return response()->json($suggestions);
            }
        } catch (\Exception $e) {
            // fallback
        }

        return response()->json([]);
    }

    public function play(Request $request)
    {
        $id = $request->input('id');
        $source = $request->input('source', 'dramabox');
        $ep = $request->input('ep', 1);

        if (!$id) {
            return redirect('/');
        }

        // Fetch detail to render Player page
        $drama = null;
        try {
            $params = ['id' => $id];
                                    if ($source === 'reelshort' || $source === 'dramabox') {
                                        $params['lang'] = 'id';
                                    }
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$source}/detail", $params);

            if ($response->successful()) {
                $detailData = $response->json();
                $drama = $detailData['data'] ?? $detailData;
            }
        } catch (\Exception $e) {
            //
        }

        if (!$drama) {
            return redirect('/')->with('error', 'Drama not found');
        }

        // Fetch active episode video URL
        $videoData = null;
        try {
            if ($source === 'dramabox') {
                $params = ['id' => $id, 'lang' => 'id'];
                $videoResponse = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/{$source}/allepisode", $params);

                if ($videoResponse->successful()) {
                    $allData = $videoResponse->json();
                    $episodes = $allData['episodes'] ?? [];
                    $matchedEpisode = null;
                    foreach ($episodes as $episode) {
                        if (isset($episode['number']) && $episode['number'] == $ep) {
                            $matchedEpisode = $episode;
                            break;
                        }
                    }

                    if ($matchedEpisode) {
                        // Extract query params from the upstream hlsUrl to forward via proxy
                        $rawHlsUrl = $matchedEpisode['hlsUrl'] ?? '';
                        $parsedUrl = parse_url($rawHlsUrl);
                        $queryString = $parsedUrl['query'] ?? '';
                        parse_str($queryString, $hlsParams);
                        $hlsParams['source'] = $source;

                        // Point to local proxy route (adds API key server-side)
                        $proxyUrl = route('drama.hls', $hlsParams, false);

                        $videoData = [
                            'number' => $matchedEpisode['number'] ?? $ep,
                            'videoUrl' => $proxyUrl,
                            'locked' => $matchedEpisode['locked'] ?? false,
                        ];
                    }
                }
            } else {
                $params = [
                    'id' => $id,
                    'ep' => $ep
                ];
                if ($source === 'reelshort' || $source === 'melolo') {
                    $params['lang'] = 'id';
                }
                $videoResponse = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/{$source}/episode", $params);

                if ($videoResponse->successful()) {
                    $videoData = $videoResponse->json();

                    $rawHlsUrl = $videoData['hlsUrl'] ?? '';
                    if (!empty($rawHlsUrl)) {
                        if ($source === 'dramawave') {
                            // Proxy Dramawave HLS (m3u8 with CDN redirect)
                            $parsedUrl = parse_url($rawHlsUrl);
                            $queryString = $parsedUrl['query'] ?? '';
                            parse_str($queryString, $hlsParams);
                            $hlsParams['source'] = $source;
                            $videoData['videoUrl'] = route('drama.hls', $hlsParams, false);
                        } elseif ($source === 'melolo') {
                            // Melolo: hlsUrl returns clean MP4 (server-decrypted CENC AES-128-CTR).
                            // Proxy through our server to attach API key server-side.
                            $videoData['videoUrl'] = route('drama.hls', [
                                'id' => $id,
                                'ep' => $ep,
                                'source' => $source,
                                'lang' => 'id',
                            ], false);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            //
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'videoData' => $videoData,
                'currentEpisode' => $ep,
                'drama' => $drama
            ]);
        }

        return view('pages.app.play', [
            'drama' => $drama,
            'source' => $source,
            'currentEpisode' => $ep,
            'videoData' => $videoData,
            'platforms' => $this->sources,
            'upstreamBaseUrl' => str_replace('/api', '', $this->baseUrl)
        ]);
    }

    public function detail(Request $request)
    {
        $id = $request->input('id');
        $source = $request->input('source', 'dramabox');

        if (!$id) {
            return response()->json(['error' => 'ID is required'], 400);
        }

        try {
            $params = ['id' => $id];
            if ($source === 'reelshort' || $source === 'dramabox') {
                $params['lang'] = 'id';
            }
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$source}/detail", $params);

            if ($response->successful()) {
                $detailData = $response->json();
                if (!isset($detailData['data'])) {
                    $detailData = [
                        'code' => 200,
                        'data' => $detailData
                    ];
                }
                return response()->json($detailData);
            }

            return response()->json(['error' => 'Failed to fetch details from upstream API'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function meloloEpisodeProxy(Request $request)
    {
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'User-Agent' => 'Mozilla/5.0'
        ])->get("{$this->baseUrl}/melolo/episode", $request->query());

        return response($response->body(), $response->status(), ['Content-Type' => 'application/json']);
    }

    public function meloloKeyProxy(Request $request)
    {
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'User-Agent' => 'Mozilla/5.0'
        ])->get("{$this->baseUrl}/melolo/key", $request->query());

        return response($response->body(), $response->status(), ['Content-Type' => 'application/json']);
    }

    /**
     * Proxy HLS playlist / stream from upstream API.
     * - For dramabox/dramawave etc.: handles m3u8 with optional 302→CDN redirect,
     *   rewrites relative segment URLs to absolute CDN URLs so hls.js can fetch them.
     * - For melolo: pass-through of server-decrypted MP4 (CENC AES-128-CTR).
     */
    public function proxyHls(Request $request)
    {
        $source = $request->query('source', 'dramabox');
        $id = $request->query('id');
        $ep = $request->query('ep');

        if (!$id || $ep === null || $ep === '') {
            return response('Missing id or ep parameter', 400);
        }

        try {
            // ── Melolo: stream MP4 with range request support ──
            if ($source === 'melolo') {
                $upstreamParams = array_filter($request->query(), fn($key) => !in_array($key, ['source']), ARRAY_FILTER_USE_KEY);

                $upstreamHeaders = [
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0',
                ];

                if ($request->header('Range')) {
                    $upstreamHeaders['Range'] = $request->header('Range');
                }

                try {
                    $client = new \GuzzleHttp\Client(['stream' => true, 'timeout' => 300]);
                    $guzzleResponse = $client->get("{$this->baseUrl}/{$source}/hls", [
                        'query' => $upstreamParams,
                        'headers' => $upstreamHeaders,
                        'allow_redirects' => [
                            'max' => 5,
                            'track_redirects' => true,
                        ],
                    ]);

                    $statusCode = $guzzleResponse->getStatusCode();
                    $body = $guzzleResponse->getBody();

                    $responseHeaders = [
                        'Content-Type' => $guzzleResponse->getHeaderLine('Content-Type') ?: 'video/mp4',
                        'Access-Control-Allow-Origin' => '*',
                        'Accept-Ranges' => 'bytes',
                        'Cache-Control' => 'public, max-age=3600',
                    ];

                    if ($guzzleResponse->hasHeader('Content-Range')) {
                        $responseHeaders['Content-Range'] = $guzzleResponse->getHeaderLine('Content-Range');
                        $statusCode = 206;
                    }
                    if ($guzzleResponse->hasHeader('Content-Length')) {
                        $responseHeaders['Content-Length'] = $guzzleResponse->getHeaderLine('Content-Length');
                    }

                    return response()->stream(function () use ($body) {
                        while (ob_get_level() > 0) {
                            ob_end_clean();
                        }
                        while (!$body->eof()) {
                            echo $body->read(65536);
                            flush();
                            if (connection_aborted()) break;
                        }
                    }, $statusCode, $responseHeaders);
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    return response('Melolo stream error: ' . $e->getMessage(), 502);
                }
            }

            // ── Other sources: HLS (m3u8) handling ──
            $upstreamUrl = "{$this->baseUrl}/{$source}/hls";
            $params = array_filter($request->query(), fn($key) => !in_array($key, ['source']), ARRAY_FILTER_USE_KEY);

            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->withoutRedirecting()->get($upstreamUrl, $params);

            $m3u8Body = null;
            $cdnBaseUrl = null;

            if ($response->status() === 302 || $response->status() === 301) {
                $cdnUrl = $response->header('Location');
                if (!$cdnUrl) {
                    return response('Redirect location missing', 502);
                }

                $cdnBaseUrl = substr($cdnUrl, 0, strrpos($cdnUrl, '/') + 1);

                $cdnResponse = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0'
                ])->get($cdnUrl);

                if ($cdnResponse->successful()) {
                    $m3u8Body = $cdnResponse->body();
                } else {
                    return response('Failed to fetch HLS from CDN', $cdnResponse->status());
                }
            } elseif ($response->successful()) {
                $m3u8Body = $response->body();
            } else {
                return response('Failed to fetch HLS playlist', $response->status());
            }

            if ($m3u8Body && $cdnBaseUrl) {
                $lines = explode("\n", $m3u8Body);
                $rewritten = [];
                foreach ($lines as $line) {
                    $trimmed = trim($line);
                    if ($trimmed !== '' && !str_starts_with($trimmed, '#') && !str_starts_with($trimmed, 'http')) {
                        $rewritten[] = $cdnBaseUrl . $trimmed;
                    } elseif (str_contains($trimmed, 'URI="') && !str_contains($trimmed, 'URI="http')) {
                        $rewritten[] = preg_replace('/URI="([^"]+)"/', 'URI="' . $cdnBaseUrl . '$1"', $trimmed);
                    } else {
                        $rewritten[] = $trimmed;
                    }
                }
                $m3u8Body = implode("\n", $rewritten);
            }

            return response($m3u8Body, 200, [
                'Content-Type' => 'application/vnd.apple.mpegurl',
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        } catch (\Exception $e) {
            return response('HLS proxy error: ' . $e->getMessage(), 500);
        }
    }
}
