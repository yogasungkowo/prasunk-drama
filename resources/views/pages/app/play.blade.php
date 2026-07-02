<x-layout.app 
    :title="($drama['title'] ?? 'Nonton Drama') . ' - Prasunk Drama'" 
    :description="isset($drama['description']) ? \Illuminate\Support\Str::limit(strip_tags($drama['description']), 150) : 'Nonton short drama china terpopuler secara gratis dengan subtitle Indonesia.'"
    :image="$drama['cover'] ?? null"
    :platforms="$platforms" 
    :selectedSource="$source"
>

    @php
        $nextEpisodeUrl = null;
        $foundCurrent = false;
        foreach($drama['episodes'] ?? [] as $ep) {
            if ($foundCurrent) {
                $nextEpisodeUrl = "?id=" . urlencode($drama['id']) . "&source=" . urlencode($source) . "&ep=" . urlencode($ep['number']);
                break;
            }
            if ($ep['number'] == $currentEpisode) {
                $foundCurrent = true;
            }
        }
    @endphp

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        {{-- Breadcrumbs & Back --}}
        <div class="mb-6">
            <a href="/?source={{ $source }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Video Player Col --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Video Container --}}
                <div id="dramabox-player-container" class="aspect-video w-full rounded-3xl overflow-hidden border border-white/5 bg-neutral-900 shadow-2xl relative">
                    @if($videoData && !empty($videoData['videoUrl']))
                        <video id="dramabox-player" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls preload="auto" autoplay></video>
                    @elseif($videoData && ($videoData['locked'] ?? false))
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-neutral-950/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Episode Terkunci</h3>
                            <p class="text-sm text-neutral-500 mt-2 max-w-sm">Episode ini memerlukan akun premium atau unlock langsung di aplikasi {{ $platforms[$source] ?? $source }}.</p>
                        </div>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <div class="h-8 w-8 animate-spin rounded-full border-4 border-red-600/30 border-t-red-600"></div>
                            <p class="text-sm text-neutral-500 mt-4">Gagal memuat player video atau video tidak tersedia.</p>
                        </div>
                    @endif
                </div>

                {{-- Auto Play Toggle --}}
                <div class="flex items-center justify-between border border-white/5 bg-white/[0.01] rounded-3xl px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-500 border border-red-500/20">
                            <i class="ri-play-list-add-line text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white">Auto Play Next Episode</h4>
                            <p class="text-xs text-neutral-400">Putar episode berikutnya secara otomatis setelah durasi habis</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="autoplay-toggle" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-neutral-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-neutral-400 after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600 peer-checked:after:bg-white peer-checked:after:border-white"></div>
                    </label>
                </div>

                {{-- Drama Information --}}
                <div class="border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <span class="inline-block rounded-full bg-red-950/40 text-red-400 px-3 py-1 text-xs font-semibold border border-red-900/20 mb-2">
                                {{ strtoupper($source) }}
                            </span>
                            <h1 class="text-2xl font-bold text-white leading-tight">
                                {{ $drama['title'] }} — <span class="text-red-400">Episode {{ $currentEpisode }}</span>
                            </h1>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                            <span class="text-xs text-neutral-500 font-medium mr-1">Bagikan:</span>
                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text={{ rawurlencode(($drama['title'] ?? 'Nonton Drama') . ' - Episode ' . $currentEpisode . ': Nonton short drama ' . ($drama['title'] ?? '') . ' di Prasunk Drama sekarang! ' . request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-green-500/20 hover:border-green-500/30 text-neutral-300 hover:text-green-400 transition" title="Bagikan ke WhatsApp">
                                <i class="ri-whatsapp-line text-sm"></i>
                            </a>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-blue-600/20 hover:border-blue-600/30 text-neutral-300 hover:text-blue-400 transition" title="Bagikan ke Facebook">
                                <i class="ri-facebook-fill text-sm"></i>
                            </a>
                            <!-- X / Twitter -->
                            <a href="https://twitter.com/intent/tweet?text={{ rawurlencode('Nonton short drama ' . ($drama['title'] ?? '') . ' - Episode ' . $currentEpisode . ' di @PrasunkDrama sekarang!') }}&url={{ rawurlencode(request()->fullUrl()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-neutral-100/10 hover:border-neutral-100/20 text-neutral-300 hover:text-white transition" title="Bagikan ke X">
                                <i class="ri-twitter-x-fill text-xs"></i>
                            </a>
                            <!-- Web Share / Copy Link -->
                            <button onclick="sharePage('{{ rawurlencode(($drama['title'] ?? 'Nonton Drama') . ' - Episode ' . $currentEpisode) }}', '{{ rawurlencode('Nonton short drama ' . ($drama['title'] ?? '') . ' di Prasunk Drama sekarang!') }}', '{{ request()->fullUrl() }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-white/[0.06] text-neutral-300 hover:text-white transition cursor-pointer" title="Salin Tautan">
                                <i class="ri-link text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/5 pt-4">
                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-widest">Sinopsis</h4>
                        <p class="text-sm text-neutral-300 mt-2 leading-relaxed">
                            {{ $drama['description'] ?? 'Tidak ada sinopsis untuk drama ini.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Playlist Col --}}
            <div class="border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4 h-fit max-h-[80vh] overflow-y-auto no-scrollbar">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Episode</h3>
                    <p class="text-xs text-neutral-500 mt-1">Pilih episode untuk langsung memutar video</p>
                </div>

                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-4 gap-2 pt-2 border-t border-white/5">
                    @foreach($drama['episodes'] ?? [] as $ep)
                        @php
                            $isCurrent = $ep['number'] == $currentEpisode;
                        @endphp
                        <a 
                            @if($ep['locked'])
                                href="#"
                                onclick="alert('Episode belum tersedia atau terkunci.'); return false;"
                            @else
                                href="?id={{ $drama['id'] }}&source={{ $source }}&ep={{ $ep['number'] }}"
                            @endif
                            class="py-3 text-xs font-bold rounded-xl text-center transition {{ $isCurrent ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : ($ep['locked'] ? 'bg-white/5 text-neutral-600 cursor-not-allowed' : 'bg-white/[0.02] text-neutral-300 border border-white/5 hover:border-red-500/20 hover:text-red-400') }}">
                            {{ $ep['number'] }}{{ $ep['locked'] ? ' 🔒' : '' }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initPlayer = () => {
                const el = document.getElementById('dramabox-player');
                if (!el) return;

                const videoUrl = @json($videoData['videoUrl'] ?? '');
                const nextEpisodeUrl = @json($nextEpisodeUrl);
                if (!videoUrl) return;

                const handleVideoEnded = () => {
                    const isAutoplay = document.getElementById('autoplay-toggle')?.checked;
                    if (isAutoplay && nextEpisodeUrl) {
                        window.location.href = nextEpisodeUrl;
                    }
                };

                // Detect if this is an HLS stream
                const isHLS = videoUrl.includes('.m3u8') || videoUrl.includes('/hls');

                if (window.videojs) {
                    if (isHLS && window.Hls && window.Hls.isSupported()) {
                        // === Strategy: hls.js as engine + Video.js as UI ===
                        const hls = new window.Hls({
                            maxBufferLength: 30,
                            maxMaxBufferLength: 60,
                        });
                        
                        hls.loadSource(videoUrl);
                        hls.attachMedia(el);
                        
                        hls.on(window.Hls.Events.MANIFEST_PARSED, () => {
                            const player = window.videojs(el, {
                                autoplay: true,
                                controls: true,
                                preload: 'auto',
                                playbackRates: [0.5, 1, 1.25, 1.5, 2],
                                sources: []
                            });

                            player.on('ended', handleVideoEnded);
                            el.play().catch(() => {});
                        });
                        
                        hls.on(window.Hls.Events.ERROR, (event, data) => {
                            if (data.fatal) {
                                console.error('HLS fatal error:', data.type, data.details);
                                if (data.type === window.Hls.ErrorTypes.NETWORK_ERROR) {
                                    hls.startLoad();
                                } else if (data.type === window.Hls.ErrorTypes.MEDIA_ERROR) {
                                    hls.recoverMediaError();
                                } else {
                                    hls.destroy();
                                }
                            }
                        });
                    } else if (isHLS && el.canPlayType('application/vnd.apple.mpegurl')) {
                        // === Safari native HLS support ===
                        el.src = videoUrl;
                        const player = window.videojs(el, {
                            autoplay: true,
                            controls: true,
                            preload: 'auto',
                            playbackRates: [0.5, 1, 1.25, 1.5, 2],
                            sources: []
                        });
                        player.on('ended', handleVideoEnded);
                        el.play().catch(() => {});
                    } else {
                        // === Non-HLS (direct mp4, etc.) ===
                        const player = window.videojs(el, {
                            autoplay: true,
                            controls: true,
                            preload: 'auto',
                            playbackRates: [0.5, 1, 1.25, 1.5, 2],
                        });
                        player.on('ended', handleVideoEnded);
                        player.src({ src: videoUrl, type: 'video/mp4' });
                    }
                } else {
                    setTimeout(initPlayer, 100);
                }
            };
            initPlayer();
        });
    </script>
    @endpush
</x-layout.app>

