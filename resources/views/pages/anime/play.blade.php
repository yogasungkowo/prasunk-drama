@php
    $downloadQualities = $episode['downloadUrl']['qualities'] ?? [];
    $batchTitle = $batchDownload['title'] ?? 'Batch Download';
    $batchFormats = $batchDownload['downloadUrl']['formats']
        ?? $batchDownload['download_url']['formats']
        ?? $batchDownload['downloads']['formats']
        ?? [];
    $batchQualities = $batchDownload['downloadUrl']['qualities']
        ?? $batchDownload['download_url']['qualities']
        ?? $batchDownload['download_links']
        ?? $batchDownload['links']
        ?? [];

    if (empty($batchFormats) && !empty($batchQualities)) {
        $batchFormats = [[
            'title' => $batchTitle . ' Batch Subtitle Indonesia',
            'qualities' => $batchQualities,
        ]];
    }

    $downloadSectionId = !empty($downloadQualities) ? 'download-section' : 'batch-download-section';
    $formatQualityLabel = function ($label) {
        $label = trim((string) $label);

        if ($label === '') {
            return 'Download';
        }

        $label = str_replace(['_', '-'], ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);
        $label = preg_replace_callback('/\b(mp4|mkv|x265|x264|hevc)\b/i', function ($matches) {
            return strtoupper($matches[1]);
        }, $label);
        $label = preg_replace_callback('/\b(\d{3,4})\s*p\b/i', function ($matches) {
            return $matches[1] . 'p';
        }, $label);

        return trim($label);
    };
@endphp

<x-layout.app :title="($episode['title'] ?? 'Nonton Anime') . ' - Prasunk Anime'" :description="'Nonton streaming anime subtitle Indonesia.'" :image="$episode['info']['poster'] ?? null">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Beranda Anime
            </a>
            
            <div class="flex items-center gap-3">
                @php
                    $prevEp = $episode['prevEpisode'] ?? null;
                    $nextEp = $episode['nextEpisode'] ?? null;
                    $prevSlug = is_array($prevEp) ? ($prevEp['episodeId'] ?? $prevEp['slug'] ?? '') : (is_string($prevEp) ? $prevEp : '');
                    $nextSlug = is_array($nextEp) ? ($nextEp['episodeId'] ?? $nextEp['slug'] ?? '') : (is_string($nextEp) ? $nextEp : '');
                @endphp
                @if(!empty($episode['hasPrevEpisode']) && !empty($prevSlug))
                <a href="{{ route('anime.episode', $prevSlug) }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-xs text-neutral-300 hover:bg-white/10 transition border border-white/5">
                    <i class="ri-arrow-left-s-line"></i> Prev
                </a>
                @endif
                @if(!empty($episode['hasNextEpisode']) && !empty($nextSlug))
                <a href="{{ route('anime.episode', $nextSlug) }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-xs text-neutral-300 hover:bg-white/10 transition border border-white/5">
                    Next <i class="ri-arrow-right-s-line"></i>
                </a>
                @endif
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="aspect-video w-full rounded-3xl overflow-hidden border border-white/10 bg-neutral-950 shadow-2xl relative group">
                    @php
                        $streamUrl = $episode['defaultStreamingUrl'] ?? '';
                    @endphp

                    @if(!empty($streamUrl))
                    <div id="main-player-wrapper" class="w-full h-full relative">
                        <iframe src="{{ $streamUrl }}" id="main-player" class="w-full h-full" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                    </div>
                    @else
                    <div id="main-player-wrapper" class="w-full h-full flex items-center justify-center relative">
                        <div class="text-center p-6">
                            <h3 class="text-lg font-semibold text-white mb-2">Pilih Server Streaming</h3>
                            <p class="text-sm text-neutral-500">Silakan pilih kualitas dan server di bawah untuk mulai memutar video.</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="border border-white/5 bg-white/[0.02] rounded-3xl p-6 md:p-8 space-y-4 backdrop-blur-sm">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white leading-tight mb-2">{{ $episode['title'] ?? 'Episode' }}</h1>
                        @if(!empty($episode['releaseTime']))
                        <p class="text-xs text-neutral-400">Dirilis pada: <span class="text-neutral-300">{{ $episode['releaseTime'] }}</span></p>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-white/5">
                        @if(!empty($episode['animeId']))
                        <a href="{{ route('anime.detail', $episode['animeId']) }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/5 border border-white/10 px-4 py-2 text-xs font-medium text-white hover:text-red-300 hover:border-red-500/30 transition shadow-lg shadow-black/20">
                            <i class="ri-information-line"></i>
                            Detail Anime
                        </a>
                        @endif
                        
                        @if(!empty($downloadQualities) || !empty($batchFormats))
                        <a href="#{{ $downloadSectionId }}" class="inline-flex items-center gap-1.5 rounded-full bg-red-600/20 border border-red-500/30 px-4 py-2 text-xs font-semibold text-red-300 hover:bg-red-600/30 transition shadow-lg shadow-red-900/20">
                            <i class="ri-download-2-line"></i>
                            Download
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6 self-start lg:col-span-1">
                {{-- Server Selection --}}
                @php $serverQualities = $episode['server']['qualities'] ?? []; @endphp
                @if(!empty($serverQualities))
                <div class="border border-white/5 bg-white/[0.02] rounded-3xl p-6 md:p-8 backdrop-blur-sm">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ri-server-line text-red-500"></i> Server Streaming
                    </h3>
                    
                    <div class="space-y-6">
                        @foreach ($serverQualities as $quality)
                        <div>
                            <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-widest mb-3">{{ $quality['title'] ?? '' }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @if(!empty($quality['serverList']))
                                    @foreach ($quality['serverList'] as $server)
                                    @php
                                        $sId = $server['serverId'] ?? '';
                                        $sName = $server['title'] ?? 'Server';
                                    @endphp
                                    <button onclick="loadAnimeServer('{{ $sId }}', this)" class="rounded-lg bg-white/5 border border-white/10 px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-red-300 hover:border-red-500/30 transition server-btn shadow-sm">
                                        {{ $sName }}
                                    </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Information --}}
                @if(!empty($episode['info']))
                <div class="border border-white/5 bg-white/[0.02] rounded-3xl p-6 md:p-8 backdrop-blur-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Informasi Episode</h3>
                    <div class="space-y-3 text-sm">
                        @if(!empty($episode['info']['type']))
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-neutral-500">Tipe</span>
                            <span class="text-white">{{ $episode['info']['type'] }}</span>
                        </div>
                        @endif
                        @if(!empty($episode['info']['duration']))
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-neutral-500">Durasi</span>
                            <span class="text-white">{{ $episode['info']['duration'] }}</span>
                        </div>
                        @endif
                        @if(!empty($episode['info']['credit']))
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-neutral-500">Credit</span>
                            <span class="text-white text-right">{{ $episode['info']['credit'] }}</span>
                        </div>
                        @endif
                        @if(!empty($episode['info']['encoder']))
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-neutral-500">Encoder</span>
                            <span class="text-white text-right">{{ $episode['info']['encoder'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            @if(!empty($downloadQualities))
            {{-- Download Section --}}
            <section id="download-section" class="col-span-1 lg:col-span-3 mb-2 mt-4">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="ri-download-cloud-2-line text-red-500"></i>
                        Link Download Episode
                    </h2>
                    <p class="text-sm text-neutral-400 mt-1">Pilih kualitas episode dan server download yang tersedia.</p>
                </div>
                
                <div class="rounded-3xl border border-white/5 bg-white/[0.02] p-5 md:p-6 backdrop-blur-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($downloadQualities as $quality)
                        @php $urls = $quality['urls'] ?? []; @endphp
                        <div class="rounded-2xl border border-white/5 bg-neutral-900/60 p-4 hover:border-white/10 transition-colors">
                            <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3 mb-4">
                                <h3 class="font-bold text-white">{{ $formatQualityLabel($quality['title'] ?? $quality['quality'] ?? 'Download') }}</h3>
                                @if(!empty($quality['size']))
                                <span class="shrink-0 rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400">{{ $quality['size'] }}</span>
                                @endif
                            </div>
                            
                            @if(!empty($urls))
                            <div class="flex flex-wrap gap-2">
                                @foreach($urls as $dl)
                                @php $url = $dl['url'] ?? $dl['link'] ?? ''; @endphp
                                @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-white hover:bg-white/20 transition-all">
                                    <i class="ri-external-link-line text-red-400"></i>
                                    {{ $dl['title'] ?? $dl['server'] ?? $dl['label'] ?? 'Link' }}
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-neutral-500">Link untuk kualitas ini belum tersedia.</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            @if(!empty($batchFormats))
            <section id="batch-download-section" class="col-span-1 lg:col-span-3 mb-8 mt-4">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="ri-archive-line text-red-500"></i>
                        Link Download Batch
                    </h2>
                    <p class="text-sm text-neutral-400 mt-1">Download semua episode sekaligus berdasarkan format dan resolusi.</p>
                </div>

                <div class="space-y-6">
                    @foreach($batchFormats as $format)
                    @php
                        $qualities = $format['qualities'] ?? [];
                        if (empty($qualities) && !empty($format['urls'])) {
                            $qualities = [$format];
                        }
                    @endphp

                    <div class="rounded-3xl border border-white/5 bg-white/[0.02] p-5 md:p-6 backdrop-blur-sm">
                        @if(!empty($format['title']))
                        <h3 class="text-base font-bold text-white mb-5">{{ $format['title'] }}</h3>
                        @endif

                        @if(!empty($qualities))
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach($qualities as $quality)
                            @php
                                $urls = $quality['urls'] ?? [];
                                if (empty($urls) && (!empty($quality['url']) || !empty($quality['link']))) {
                                    $urls = [[
                                        'title' => $quality['server'] ?? $quality['label'] ?? $quality['title'] ?? 'Download',
                                        'url' => $quality['url'] ?? $quality['link'],
                                    ]];
                                }
                            @endphp

                            <div class="rounded-2xl border border-white/5 bg-neutral-900/60 p-4 hover:border-white/10 transition-colors">
                                <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3 mb-4">
                                    <h4 class="font-bold text-white">{{ $formatQualityLabel($quality['title'] ?? $quality['quality'] ?? 'Download') }}</h4>
                                    @if(!empty($quality['size']))
                                    <span class="shrink-0 rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400">{{ $quality['size'] }}</span>
                                    @endif
                                </div>

                                @if(!empty($urls))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($urls as $link)
                                    @php $url = $link['url'] ?? $link['link'] ?? ''; @endphp
                                    @if($url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-white hover:bg-white/20 transition-all">
                                        <i class="ri-external-link-line text-red-400"></i>
                                        {{ $link['title'] ?? $link['server'] ?? $link['label'] ?? 'Link' }}
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-neutral-500">Link untuk kualitas ini belum tersedia.</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-neutral-500">Belum ada kualitas download untuk format ini.</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            @include('components.anime.related-grid', [
                'relatedAnime' => $relatedAnime ?? null,
                'sectionClass' => 'col-span-1 lg:col-span-3 mb-8 mt-4',
            ])

        </div>
    </div>

    @push('scripts')
    <script>
        async function loadAnimeServer(serverId, btn) {
            const container = document.getElementById('main-player-wrapper');
            if (!container) return;

            // Update active state of buttons
            document.querySelectorAll('.server-btn').forEach(b => {
                b.classList.remove('bg-red-600/20', 'border-red-500/30', 'text-red-300');
                b.classList.add('bg-white/5', 'border-white/10', 'text-neutral-300');
            });
            if (btn) {
                btn.classList.remove('bg-white/5', 'border-white/10', 'text-neutral-300');
                btn.classList.add('bg-red-600/20', 'border-red-500/30', 'text-red-300');
            }

            const mainPlayer = document.getElementById('main-player');
            if (mainPlayer) {
                mainPlayer.style.display = 'none';
            }
            
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'player-loading-overlay';
            loadingOverlay.className = 'absolute inset-0 flex items-center justify-center bg-neutral-950 z-20 rounded-3xl';
            loadingOverlay.innerHTML = '<div class="h-8 w-8 animate-spin rounded-full border-4 border-red-600/30 border-t-red-600"></div><p class="text-sm text-neutral-500 ml-3">Memuat server...</p>';
            container.appendChild(loadingOverlay);

            try {
                const res = await fetch(`/anime/server/${serverId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                
                const embedUrl = data?.data?.url ?? data?.url ?? '';
                
                loadingOverlay.remove();

                if (embedUrl) {
                    if (mainPlayer) {
                        mainPlayer.src = embedUrl;
                        mainPlayer.style.display = 'block';
                    } else {
                        container.innerHTML = `<iframe src="${embedUrl}" id="main-player" class="w-full h-full absolute inset-0" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>`;
                    }
                } else {
                    container.innerHTML = '<div class="absolute inset-0 flex items-center justify-center bg-neutral-950 text-neutral-500 rounded-3xl">Gagal memuat server. Coba server lainnya.</div>';
                }
            } catch (err) {
                loadingOverlay.remove();
                container.innerHTML = '<div class="absolute inset-0 flex items-center justify-center bg-neutral-950 text-neutral-500 rounded-3xl">Terjadi kesalahan saat memuat server.</div>';
            }
        }
    </script>
    @endpush
</x-layout.app>
