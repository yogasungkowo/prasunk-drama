<x-layout.app :title="($episode['title'] ?? 'Nonton Anime') . ' - Prasunk Anime'" :description="'Nonton streaming anime subtitle Indonesia.'" :image="$episode['info']['poster'] ?? null">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ isset($episode['animeId']) ? route('anime.detail', $episode['animeId']) : 'javascript:history.back()' }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Anime
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
                        
                        @if(!empty($episode['downloadUrl']) && isset($episode['downloadUrl']['qualities']))
                        <a href="#download-section" class="inline-flex items-center gap-1.5 rounded-full bg-red-600/20 border border-red-500/30 px-4 py-2 text-xs font-semibold text-red-300 hover:bg-red-600/30 transition shadow-lg shadow-red-900/20">
                            <i class="ri-download-2-line"></i>
                            Download
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6 max-h-[80vh] overflow-y-auto no-scrollbar lg:col-span-1">
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
            
            @php $downloadQualities = $episode['downloadUrl']['qualities'] ?? []; @endphp
            @if(!empty($downloadQualities))
            {{-- Download Section --}}
            <div id="download-section" class="col-span-1 lg:col-span-3 border border-white/5 bg-white/[0.02] rounded-3xl p-6 md:p-8 backdrop-blur-sm mb-8 mt-4">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="ri-download-cloud-2-line text-red-500"></i> Link Download
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($downloadQualities as $format)
                    <div class="bg-neutral-900/50 rounded-2xl p-4 border border-white/5 hover:border-white/10 transition-colors">
                        <div class="flex items-center justify-between mb-4 border-b border-white/10 pb-3">
                            <h4 class="font-bold text-white">{{ $format['title'] ?? '' }}</h4>
                            @if(!empty($format['size']))
                            <span class="text-xs font-medium text-red-400 bg-red-400/10 px-2 py-1 rounded-md">{{ $format['size'] }}</span>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            @if(!empty($format['urls']))
                                @foreach($format['urls'] as $dl)
                                <a href="{{ $dl['url'] ?? '#' }}" target="_blank" class="rounded-lg bg-white/5 border border-white/10 px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-white hover:bg-white/20 transition-all shadow-sm">
                                    {{ $dl['title'] ?? 'Link' }}
                                </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

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
                const res = await fetch(`/anime/server/${serverId}`);
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
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @endpush
</x-layout.app>
