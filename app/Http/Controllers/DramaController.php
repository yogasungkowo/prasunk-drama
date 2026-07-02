<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class DramaController extends Controller
{
    private $apiKey = 'ANICHIN-38AC2EEDF66F1F2B74A175811F7ADEDA';
    private $baseUrl = 'https://api.anichin.bio';

    private $sources = [
        'dramabox' => 'DramaBox',
        'reelshort' => 'ReelShort',
        'shortmax' => 'ShortMax',
        'netshort' => 'NetShort',
        'goodshort' => 'GoodShort',
        'dramawave' => 'DramaWave',
        'flickreels' => 'FlickReels',
        'freereels' => 'FreeReels',
        'stardusttv' => 'StardustTV',
        'idrama' => 'iDrama',
        'dramanova' => 'DramaNova',
        'starshort' => 'StarShort',
        'dramabite' => 'DramaBite',
        'melolo' => 'Melolo',
        'moboreels' => 'MoboReels'
    ];

    public function index(Request $request)
    {
        $selectedSource = $request->input('source', 'dramabox');
        $searchQuery = $request->input('search');

        if (!array_key_exists($selectedSource, $this->sources)) {
            $selectedSource = 'dramabox';
        }

        $dramas = [];

        try {
            if ($searchQuery) {
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/{$selectedSource}/search", [
                    'query' => $searchQuery
                ]);
            } else {
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'User-Agent' => 'Mozilla/5.0'
                ])->get("{$this->baseUrl}/{$selectedSource}/trending");
            }

            if ($response->successful()) {
                $data = $response->json();
                $items = $data['items'] ?? [];

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
        } catch (\Exception $e) {
            // Silence errors
        }

        $allDramasCollection = collect($dramas);
        $perPage = 12;
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
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$selectedSource}/search", [
                'query' => $query
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $items = $data['items'] ?? [];
                $suggestions = [];

                // Limit suggestions to top 5 for neat list overlay
                foreach (array_slice($items, 0, 5) as $item) {
                    $suggestions[] = [
                        'id' => $item['id'] ?? $item['dramaId'] ?? '',
                        'title' => $item['title'] ?? 'Unknown Title',
                        'description' => str_limit($item['description'] ?? $item['synopsis'] ?? 'No synopsis.', 90),
                        'cover' => $item['cover'] ?? $item['posterImg'] ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop',
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
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$source}/detail", [
                'id' => $id
            ]);

            if ($response->successful()) {
                $detailData = $response->json();
                $drama = $detailData['data'] ?? null;
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
            $videoResponse = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$source}/episode", [
                'id' => $id,
                'ep' => $ep
            ]);

            if ($videoResponse->successful()) {
                $videoData = $videoResponse->json();
            }
        } catch (\Exception $e) {
            //
        }

        return view('pages.app.play', [
            'drama' => $drama,
            'source' => $source,
            'currentEpisode' => $ep,
            'videoData' => $videoData,
            'platforms' => $this->sources
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
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'User-Agent' => 'Mozilla/5.0'
            ])->get("{$this->baseUrl}/{$source}/detail", [
                'id' => $id
            ]);

            if ($response->successful()) {
                $detailData = $response->json();
                return response()->json($detailData);
            }

            return response()->json(['error' => 'Failed to fetch details from upstream API'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
