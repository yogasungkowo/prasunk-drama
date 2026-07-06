<x-layout.app title="Prasunk Anime - Nonton & Streaming Anime Sub Indo" description="Nonton streaming anime subtitle Indonesia gratis. Anime ongoing, anime tamat, dan rekomendasi anime terbaru setiap hari.">

    {{-- Hero Section --}}
    <section class="relative w-full px-6 pt-12 pb-12 lg:px-8 lg:pt-20 lg:pb-16">
        {{-- Background Effects --}}
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-red-900/20 via-neutral-900 to-neutral-950"></div>
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 blur-3xl opacity-30">
                <div class="aspect-square w-[600px] rounded-full bg-gradient-to-tr from-red-500 to-red-300"></div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl relative z-20">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="font-display text-7xl text-red-400">Streaming Anime Gratis</p>
                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Daftar Anime <span class="text-red-500">Sub Indo</span></h1>
                    <p class="mt-4 text-base leading-relaxed text-neutral-400 max-w-lg">
                        Nonton streaming anime subtitle Indonesia gratis & terupdate. Update episode terbaru setiap hari dengan kualitas HD.
                    </p>
                </div>

                <div class="w-full md:max-w-md relative" id="animeSearchContainer">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                        <input type="text" id="animeSearchInput" autocomplete="off" placeholder="Cari anime favoritmu..." class="relative w-full bg-neutral-900/80 border border-white/10 rounded-2xl px-6 py-4 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/50 transition shadow-2xl backdrop-blur-xl">
                        <div class="absolute right-6 top-4 text-neutral-400 group-hover:text-red-400 transition-colors">
                            <i class="ri-search-line text-lg"></i>
                        </div>
                    </div>
                    <div id="animeSuggestionBox" class="absolute left-0 right-0 mt-3 z-50 hidden rounded-2xl border border-white/10 bg-neutral-900/95 p-2 shadow-2xl backdrop-blur-xl max-h-[400px] overflow-y-auto custom-scrollbar"></div>
                </div>
            </div>

            <div class="mt-12 flex flex-wrap items-center gap-3">
                <a href="{{ route('anime.ongoing') }}" class="group relative overflow-hidden rounded-full border border-red-500/30 bg-red-500/10 px-5 py-2 text-sm font-medium text-red-300 transition-all hover:border-red-500/50 hover:bg-red-500/20 hover:shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                    Sedang Tayang
                </a>
                <a href="{{ route('anime.complete') }}" class="rounded-full border border-white/10 bg-white/[0.03] px-5 py-2 text-sm font-medium text-neutral-300 transition hover:border-white/20 hover:bg-white/[0.05] hover:text-white">
                    Anime Tamat
                </a>
                <a href="{{ route('anime.genre') }}" class="rounded-full border border-white/10 bg-white/[0.03] px-5 py-2 text-sm font-medium text-neutral-300 transition hover:border-white/20 hover:bg-white/[0.05] hover:text-white">
                    Genre
                </a>
                <a href="{{ route('anime.schedule') }}" class="rounded-full border border-white/10 bg-white/[0.03] px-5 py-2 text-sm font-medium text-neutral-300 transition hover:border-white/20 hover:bg-white/[0.05] hover:text-white">
                    Jadwal Rilis
                </a>
            </div>
        </div>
    </section>

    {{-- Ongoing Anime / Terbaru Section --}}
    <section class="mx-auto w-full max-w-7xl px-6 pb-4 lg:px-8">
        <div class="border-t border-white/5 pt-8">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="ri-tv-2-line text-blue-400"></i> Anime Terbaru & Sedang Tayang
            </h2>
            <p class="text-sm text-neutral-500 mt-1">Jelajahi episode anime terbaru dan temukan tontonan favoritmu</p>
        </div>
    </section>

    @if(!empty($ongoingAnime))
    <section class="mx-auto w-full max-w-7xl px-6 pb-12 lg:px-8">
        <div id="ongoingGrid" class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 relative transition-opacity duration-300">
            @foreach ($ongoingAnime as $anime)
            @php $slug = $anime['animeId'] ?? ''; @endphp
            @continue(empty($slug))
            <a href="{{ route('anime.detail', $slug) }}" class="group relative flex flex-col rounded-2xl bg-neutral-900 border border-white/5 overflow-hidden hover:border-red-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                <div class="aspect-[3/4] w-full relative overflow-hidden bg-neutral-950">
                    <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:opacity-80" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    
                    @if(!empty($anime['episodes']))
                    <div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg backdrop-blur-md bg-opacity-90">
                        Ep {{ $anime['episodes'] }}
                    </div>
                    @endif
                </div>
                <div class="p-4 flex flex-col flex-1 z-10 -mt-8">
                    <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2 group-hover:text-red-400 transition-colors drop-shadow-md">{{ $anime['title'] }}</h3>
                    <div class="mt-auto pt-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2 py-1 font-medium text-neutral-300">
                            <i class="ri-calendar-line text-neutral-400"></i> {{ $anime['releaseDay'] ?? 'Ongoing' }}
                        </span>
                        <span class="text-neutral-400">{{ $anime['latestReleaseDate'] ?? '' }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @php
            $prevPage = max(1, $currentPage - 1);
            $nextPage = $currentPage + 1;
        @endphp
        <nav id="ongoingPagination" class="flex items-center justify-center gap-2 mt-10" data-current-page="{{ $currentPage }}">
            <button onclick="loadOngoingPage({{ $prevPage }})" class="btn-prev {{ $currentPage <= 1 ? 'hidden' : 'inline-flex' }} h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
            <span class="page-indicator inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-600 text-sm font-semibold text-white shadow-lg shadow-red-600/30">{{ $currentPage }}</span>
            <button onclick="loadOngoingPage({{ $nextPage }})" class="btn-next inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </button>
        </nav>
    </section>
    @endif

    {{-- Completed Anime Section --}}
    @if(!empty($homeData['completed']['animeList']))
    <section class="mx-auto w-full max-w-7xl px-6 pb-16 lg:px-8">
        <div class="flex items-end justify-between mb-8 border-t border-white/5 pt-8">
            <div>
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-green-500"></i> Rekomendasi Anime Tamat
                </h2>
                <p class="text-sm text-neutral-400 mt-1">Binge-watch anime yang sudah selesai</p>
            </div>
            <a href="{{ route('anime.complete') }}" class="hidden sm:flex items-center gap-1 text-sm font-medium text-red-400 hover:text-red-300 transition">
                Lihat Semua <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach (array_slice($homeData['completed']['animeList'], 0, 10) as $anime)
            @php $slug = $anime['animeId'] ?? ''; @endphp
            @continue(empty($slug))
            <a href="{{ route('anime.detail', $slug) }}" class="group relative flex flex-col rounded-2xl bg-neutral-900 border border-white/5 overflow-hidden hover:border-red-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                <div class="aspect-[3/4] w-full relative overflow-hidden bg-neutral-950">
                    <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:opacity-80" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    
                    @if(!empty($anime['episodes']))
                    <div class="absolute top-3 right-3 bg-neutral-800 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg backdrop-blur-md bg-opacity-90 border border-white/10">
                        {{ $anime['episodes'] }} Eps
                    </div>
                    @endif
                </div>
                <div class="p-4 flex flex-col flex-1 z-10 -mt-8">
                    <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2 group-hover:text-red-400 transition-colors drop-shadow-md">{{ $anime['title'] }}</h3>
                    <div class="mt-auto pt-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-green-500/10 px-2 py-1 font-medium text-green-400">
                            <i class="ri-check-line"></i> Tamat
                        </span>
                        @if(!empty($anime['score']))
                        <span class="text-yellow-400 font-medium"><i class="ri-star-fill text-[10px]"></i> {{ $anime['score'] }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6 sm:hidden">
            <a href="{{ route('anime.complete') }}" class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 py-3 text-sm font-medium text-white transition hover:bg-white/10">
                Lihat Semua Tamat <i class="ri-arrow-right-line"></i>
            </a>
        </div>
    </section>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('animeSearchInput');
            const suggestionBox = document.getElementById('animeSuggestionBox');
            let searchTimeout = null;

            if (searchInput && suggestionBox) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        suggestionBox.classList.add('hidden');
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetch(`/anime/search-ajax?q=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.length === 0) {
                                    suggestionBox.innerHTML = '<div class="p-6 text-sm text-neutral-400 text-center flex flex-col items-center gap-2"><i class="ri-search-line text-2xl opacity-50"></i><span>Tidak ada hasil ditemukan</span></div>';
                                    suggestionBox.classList.remove('hidden');
                                    return;
                                }

                                let html = '';
                                data.forEach(item => {
                                    const slug = item.animeId || item.slug || item.id || '';
                                    const rating = item.score || item.rating || '';
                                    const synopsis = item.synopsis || item.description || 'Tidak ada deskripsi.';
                                    html += `
                                        <a href="/anime/anime/${slug}" onclick="window.location.href='/anime/anime/${slug}'; return false;" class="flex items-center gap-4 p-3 rounded-xl transition hover:bg-white/[0.06] group relative z-50 cursor-pointer pointer-events-auto">
                                            <div class="w-14 h-20 rounded-lg overflow-hidden bg-neutral-950 shrink-0 shadow-md pointer-events-none">
                                                <img src="${item.poster || item.cover || item.thumbnail || ''}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                            </div>
                                            <div class="flex-1 min-w-0 pointer-events-none">
                                                <div class="flex items-center justify-between gap-2">
                                                    <h4 class="text-sm font-semibold text-white truncate group-hover:text-red-400 transition">${item.title}</h4>
                                                    ${rating ? `<span class="text-xs font-medium text-yellow-400 shrink-0 bg-yellow-400/10 px-1.5 py-0.5 rounded flex items-center gap-1"><i class="ri-star-fill text-[10px]"></i> ${rating}</span>` : ''}
                                                </div>
                                                <p class="text-xs text-neutral-400 line-clamp-2 mt-1.5 leading-relaxed">${synopsis}</p>
                                            </div>
                                        </a>
                                    `;
                                });
                                suggestionBox.innerHTML = html;
                                suggestionBox.classList.remove('hidden');
                            })
                            .catch(err => console.error('Anime suggest error:', err));
                    }, 400);
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = this.value.trim();
                        if (query.length > 0) {
                            window.location.href = `/anime/search/${encodeURIComponent(query)}`;
                        }
                    }
                });

                document.addEventListener('click', function(e) {
                    const container = document.getElementById('animeSearchContainer');
                    if (container && !container.contains(e.target)) {
                        suggestionBox.classList.add('hidden');
                    }
                });
            }
        });

        function loadOngoingPage(page) {
            const grid = document.getElementById('ongoingGrid');
            const nav = document.getElementById('ongoingPagination');
            if (!grid || !nav) return;

            grid.classList.add('opacity-50');
            
            fetch(`/anime/ongoing-ajax?page=${page}`)
                .then(res => res.json())
                .then(res => {
                    if(res.data && res.data.length > 0) {
                        let html = '';
                        res.data.forEach(anime => {
                            const slug = anime.animeId || anime.slug || '';
                            if(!slug) return;
                            
                            html += `
                                <a href="/anime/anime/${slug}" class="group relative flex flex-col rounded-2xl bg-neutral-900 border border-white/5 overflow-hidden hover:border-red-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                                    <div class="aspect-[3/4] w-full relative overflow-hidden bg-neutral-950">
                                        <img src="${anime.poster || ''}" alt="${anime.title || ''}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:opacity-80" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                                        
                                        ${anime.episodes ? `
                                        <div class="absolute top-3 right-3 bg-red-600 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg backdrop-blur-md bg-opacity-90">
                                            Ep ${anime.episodes}
                                        </div>` : ''}
                                    </div>
                                    <div class="p-4 flex flex-col flex-1 z-10 -mt-8">
                                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2 group-hover:text-red-400 transition-colors drop-shadow-md">${anime.title || ''}</h3>
                                        <div class="mt-auto pt-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                                            <span class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2 py-1 font-medium text-neutral-300">
                                                <i class="ri-calendar-line text-neutral-400"></i> ${anime.releaseDay || 'Ongoing'}
                                            </span>
                                            <span class="text-neutral-400">${anime.latestReleaseDate || ''}</span>
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        
                        grid.innerHTML = html;
                        
                        nav.setAttribute('data-current-page', page);
                        nav.querySelector('.page-indicator').textContent = page;
                        nav.querySelector('.btn-prev').setAttribute('onclick', `loadOngoingPage(${Math.max(1, page - 1)})`);
                        nav.querySelector('.btn-next').setAttribute('onclick', `loadOngoingPage(${page + 1})`);
                        
                        if(page <= 1) nav.querySelector('.btn-prev').classList.replace('inline-flex', 'hidden');
                        else nav.querySelector('.btn-prev').classList.replace('hidden', 'inline-flex');

                        // Scroll back to top of grid
                        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    grid.classList.remove('opacity-50');
                })
                .catch(err => { console.error('Failed to load pagination:', err); grid.classList.remove('opacity-50'); });
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
    @endpush
</x-layout.app>
