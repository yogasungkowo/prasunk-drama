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
                {{-- HEVC Warning Banner (Hidden by default) --}}
                <div id="hevc-warning" class="hidden flex items-start gap-4 rounded-3xl bg-amber-500/10 border border-amber-500/20 p-5 text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-amber-400">Browser Anda mungkin tidak mendukung format video ini</h4>
                        <p class="text-xs mt-1 text-amber-500/80 leading-relaxed">
                            Video ini menggunakan format <b>HEVC (H.265)</b>. Jika Anda hanya mendengar suara namun layar tetap hitam/blank, silakan buka halaman ini menggunakan <b>Safari, Firefox, Microsoft Edge</b>, atau melalui <b>Browser HP (Android/iOS)</b>.
                        </p>
                    </div>
                </div>

                {{-- Video Container --}}
                <div id="dramabox-player-container" class="aspect-video w-full rounded-3xl overflow-hidden border border-white/5 bg-neutral-900 shadow-2xl relative">
                    @if($videoData && !empty($videoData['videoUrl']))
                        <video id="dramabox-player" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls preload="auto" autoplay playsinline data-source="{{ $source }}"></video>
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
                                {{ $drama['title'] }} — <span class="text-red-400">Episode <span id="current-episode-display">{{ $currentEpisode }}</span></span>
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
            <div class="lg:hidden border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4 h-fit max-h-[60vh] overflow-y-auto no-scrollbar">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Episode</h3>
                    <p class="text-xs text-neutral-500 mt-1">Scroll untuk melihat episode lainnya</p>
                </div>

                <div id="daftar-episode-container-mobile" class="flex flex-nowrap gap-2 pt-2 border-t border-white/5 overflow-x-auto pb-2">
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
                            class="flex-shrink-0 w-14 py-3 text-xs font-bold rounded-xl text-center transition {{ $isCurrent ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : ($ep['locked'] ? 'bg-white/5 text-neutral-600 cursor-not-allowed' : 'bg-white/[0.02] text-neutral-300 border border-white/5 hover:border-red-500/20 hover:text-red-400') }}">
                            {{ $ep['number'] }}{{ $ep['locked'] ? ' 🔒' : '' }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Desktop Playlist --}}
            <div class="hidden lg:block border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4 h-fit max-h-[80vh] overflow-y-auto no-scrollbar">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Episode</h3>
                    <p class="text-xs text-neutral-500 mt-1">Pilih episode untuk langsung memutar video</p>
                </div>

                <div id="daftar-episode-container" class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-4 gap-2 pt-2 border-t border-white/5">
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
    @if($source === 'melolo')
    <script src="{{ $upstreamBaseUrl }}/melolo-decrypt.js"></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentNextUrl = @json($nextEpisodeUrl);
            let currentHls = null;

            const source = @json($source);
            const isMelolo = source === 'melolo';
            
            if (isMelolo) {
                const el = document.getElementById('dramabox-player');
                if (el) {
                    const checkActualVideoDecoding = () => {
                        // Jika video berjalan tapi resolusinya 0x0, artinya browser gagal merender video track (hanya audio)
                        if (el.videoWidth === 0 && el.readyState >= 2) {
                            const warningBanner = document.getElementById('hevc-warning');
                            if (warningBanner) warningBanner.classList.remove('hidden');
                        } else if (el.videoWidth > 0) {
                            const warningBanner = document.getElementById('hevc-warning');
                            if (warningBanner) warningBanner.classList.add('hidden');
                        }
                    };
                    el.addEventListener('loadeddata', checkActualVideoDecoding);
                    el.addEventListener('playing', checkActualVideoDecoding);
                }
            }

            function setupQualitySelector(player, hls) {
                const levels = hls.levels;
                if (!levels || levels.length <= 1) return;

                const oldBtn = player.el().querySelector('.vjs-quality-selector');
                if (oldBtn) oldBtn.remove();

                const btn = document.createElement('button');
                btn.className = 'vjs-quality-selector vjs-control vjs-button';
                btn.innerHTML = '<span class="vjs-icon-placeholder">Auto</span>';

                const menu = document.createElement('div');
                menu.className = 'vjs-menu';
                menu.innerHTML = '<div class="vjs-menu-content"></div>';

                const content = menu.firstChild;

                function addItem(label, levelIndex, selected) {
                    const item = document.createElement('button');
                    item.textContent = label;
                    item.className = 'vjs-menu-item' + (selected ? ' vjs-selected' : '');
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        hls.currentLevel = levelIndex;
                        btn.querySelector('.vjs-icon-placeholder').textContent = label;
                        content.querySelectorAll('.vjs-menu-item').forEach(i => i.classList.remove('vjs-selected'));
                        item.classList.add('vjs-selected');
                        menu.style.display = 'none';
                    });
                    content.appendChild(item);
                }

                addItem('Auto', -1, true);
                levels.forEach((level, i) => {
                    const label = (level.height ? level.height + 'p' : 'Level ' + i);
                    addItem(label, i, false);
                });

                btn.appendChild(menu);
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                });

                const cb = player.controlBar.el();
                const fs = player.controlBar.fullscreenToggle && player.controlBar.fullscreenToggle.el();
                if (fs) fs.parentNode.insertBefore(btn, fs);
                else cb.appendChild(btn);

                document.addEventListener('click', () => { menu.style.display = 'none'; });
            }

            function rebuildQualitySelector(player) {
                if (currentHls && currentHls.levels && currentHls.levels.length > 1) {
                    setupQualitySelector(player, currentHls);
                }
            }

            function updateEpisodeGrid(data) {
                document.querySelectorAll('#daftar-episode-container a, #daftar-episode-container-mobile a').forEach(btn => {
                    const rawText = btn.textContent.trim();
                    const epNum = rawText.split(' ')[0];
                    if (epNum == data.currentEpisode) {
                        btn.className = "flex-shrink-0 w-14 py-3 text-xs font-bold rounded-xl text-center transition bg-red-600 text-white shadow-lg shadow-red-600/20";
                    } else if (!rawText.includes('🔒')) {
                        btn.className = "flex-shrink-0 w-14 py-3 text-xs font-bold rounded-xl text-center transition bg-white/[0.02] text-neutral-300 border border-white/5 hover:border-red-500/20 hover:text-red-400";
                    }
                });
            }

            const initPlayer = () => {
                const el = document.getElementById('dramabox-player');
                if (!el) return;

                const initialVideoUrl = @json($videoData['videoUrl'] ?? '');
                if (!initialVideoUrl) return;

                const source = el.dataset.source;
                const isMelolo = source === 'melolo';

                const handleVideoEnded = async () => {
                    const isAutoplay = document.getElementById('autoplay-toggle')?.checked;
                    if (isAutoplay && currentNextUrl) {
                        try {
                            const res = await fetch(currentNextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                            const data = await res.json();
                            
                            if (data.videoData && data.videoData.videoUrl && !data.videoData.locked) {
                                window.history.pushState({}, '', currentNextUrl);
                                
                                const newUrl = data.videoData.videoUrl;
                                if (isMelolo) {
                                    el.classList.remove('video-js', 'vjs-default-skin', 'vjs-big-play-centered');
                                    el.classList.add('w-full', 'h-full', 'rounded-3xl');
                                    el.style.backgroundColor = 'black';

                                    if (window.MeloloDecrypt) {
                                        window.MeloloDecrypt.play(el, {
                                            apiBase: window.location.origin,
                                            bookId: @json($drama['id']),
                                            episode: data.currentEpisode,
                                            quality: '720p',
                                            onProgress: (phase, msg) => console.log(`[Melolo] ${phase}: ${msg}`)
                                        }).then(() => {
                                            el.removeEventListener('ended', handleVideoEnded);
                                            el.addEventListener('ended', handleVideoEnded);
                                        }).catch(err => console.error('Melolo Decrypt Error:', err));
                                    } else {
                                        el.src = newUrl;
                                        el.removeEventListener('ended', handleVideoEnded);
                                        el.addEventListener('ended', handleVideoEnded);
                                        el.play().catch(() => {});
                                    }
                                } else {
                                    const player = window.videojs(el);
                                    const isHLS = newUrl.includes('.m3u8') || newUrl.includes('/hls');
                                    if (isHLS && window.Hls && window.Hls.isSupported()) {
                                        if (currentHls) currentHls.destroy();
                                        currentHls = new window.Hls({ maxBufferLength: 30, maxMaxBufferLength: 60 });
                                        currentHls.loadSource(newUrl);
                                        currentHls.attachMedia(el);
                                        currentHls.on(window.Hls.Events.MANIFEST_PARSED, () => {
                                            player.play().catch(() => {});
                                            rebuildQualitySelector(player);
                                        });
                                    } else if (isHLS && el.canPlayType('application/vnd.apple.mpegurl')) {
                                        player.src({ src: newUrl, type: 'application/x-mpegURL' });
                                        player.play().catch(() => {});
                                    } else {
                                        player.src({ src: newUrl, type: 'video/mp4' });
                                        player.play().catch(() => {});
                                    }
                                }
                                
                                let found = false;
                                currentNextUrl = null;
                                for (const ep of data.drama.episodes) {
                                    if (found && !ep.locked) {
                                        const searchParams = new URLSearchParams(window.location.search);
                                        searchParams.set('ep', ep.number);
                                        currentNextUrl = '?' + searchParams.toString();
                                        break;
                                    }
                                    if (ep.number == data.currentEpisode) found = true;
                                }
                                
                                updateEpisodeGrid(data);

                                const epDisplay = document.getElementById('current-episode-display');
                                if (epDisplay) epDisplay.innerText = data.currentEpisode;
                            } else {
                                window.location.href = currentNextUrl;
                            }
                        } catch (err) {
                            window.location.href = currentNextUrl;
                        }
                    }
                };

                if (window.videojs) {
                    if (isMelolo) {
                        el.classList.remove('video-js', 'vjs-default-skin', 'vjs-big-play-centered');
                        el.classList.add('w-full', 'h-full', 'rounded-3xl');
                        el.style.backgroundColor = 'black';
                        
                        if (window.MeloloDecrypt) {
                            window.MeloloDecrypt.play(el, {
                                apiBase: window.location.origin,
                                bookId: @json($drama['id']),
                                episode: @json($currentEpisode),
                                quality: '720p',
                                onProgress: (phase, msg) => console.log(`[Melolo] ${phase}: ${msg}`)
                            }).then(() => {
                                el.addEventListener('ended', handleVideoEnded);
                            }).catch(err => console.error('Melolo Decrypt Error:', err));
                        } else {
                            el.src = initialVideoUrl;
                            el.addEventListener('ended', handleVideoEnded);
                            el.play().catch(() => {});
                        }
                    } else {
                        const isHLS = initialVideoUrl.includes('.m3u8') || initialVideoUrl.includes('/hls');

                        if (isHLS && window.Hls && window.Hls.isSupported()) {
                            currentHls = new window.Hls({
                                maxBufferLength: 30,
                                maxMaxBufferLength: 60,
                            });
                            
                            currentHls.loadSource(initialVideoUrl);
                            currentHls.attachMedia(el);
                            
                            currentHls.on(window.Hls.Events.MANIFEST_PARSED, () => {
                                const player = window.videojs(el, {
                                    autoplay: true,
                                    controls: true,
                                    preload: 'auto',
                                    playbackRates: [0.5, 1, 1.25, 1.5, 2],
                                    sources: []
                                });

                                player.on('ended', handleVideoEnded);
                                el.play().catch(() => {});
                                rebuildQualitySelector(player);
                            });
                            
                            currentHls.on(window.Hls.Events.ERROR, (event, data) => {
                                if (data.fatal) {
                                    if (data.type === window.Hls.ErrorTypes.NETWORK_ERROR) {
                                        currentHls.startLoad();
                                    } else if (data.type === window.Hls.ErrorTypes.MEDIA_ERROR) {
                                        currentHls.recoverMediaError();
                                    } else {
                                        currentHls.destroy();
                                    }
                                }
                            });
                        } else if (isHLS && el.canPlayType('application/vnd.apple.mpegurl')) {
                            el.src = initialVideoUrl;
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
                            const player = window.videojs(el, {
                                autoplay: true,
                                controls: true,
                                preload: 'auto',
                                playbackRates: [0.5, 1, 1.25, 1.5, 2],
                            });
                            player.on('ended', handleVideoEnded);
                            player.src({ src: initialVideoUrl, type: 'video/mp4' });
                        }
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

