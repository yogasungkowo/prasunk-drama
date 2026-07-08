<x-layout.app title="Prasunk Anime - Nonton & Streaming Anime Sub Indo" description="Nonton streaming anime subtitle Indonesia gratis. Anime ongoing, anime tamat, dan rekomendasi anime terbaru setiap hari.">
    @php
        $heroAnime = collect($ongoingAnime ?? [])
            ->filter(fn ($anime) => !empty($anime['animeId']) && !empty($anime['poster']))
            ->sortByDesc(function ($anime) {
                $rating = $anime['score'] ?? $anime['rating'] ?? 0;
                return (float) preg_replace('/[^0-9.]/', '', (string) $rating);
            })
            ->take(5)
            ->values()
            ->all();
    @endphp

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="mx-auto w-full max-w-7xl px-3 pt-4 pb-6 sm:px-6 sm:pt-6 sm:pb-8 lg:px-8 lg:pt-8">
            <div id="animeHeroSlider" class="relative min-h-[540px] overflow-hidden rounded-2xl border border-white/10 bg-neutral-950 shadow-2xl shadow-black/40 sm:min-h-[520px] sm:rounded-3xl lg:min-h-[560px]">
                @forelse ($heroAnime as $index => $anime)
                    @php
                        $slug = $anime['animeId'] ?? '';
                        $rating = $anime['score'] ?? $anime['rating'] ?? '';
                        $watchUrl = route('anime.detail', $slug) . '#episodes';
                    @endphp
                    <article class="anime-hero-slide absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? 'is-active opacity-100' : 'opacity-0' }}" data-hero-slide>
                        <div class="anime-hero-bg absolute inset-0 bg-cover bg-center transition-transform duration-[6500ms] ease-out" style="background-image: url('{{ $anime['cover'] ?? $anime['poster'] ?? '' }}');"></div>
                        <div class="absolute inset-0 bg-neutral-950/45 sm:hidden"></div>
                        <div class="absolute inset-0 bg-linear-to-r from-neutral-950 via-neutral-950/82 to-neutral-950/35"></div>
                        <div class="absolute inset-0 bg-linear-to-t from-neutral-950 via-neutral-950/45 to-neutral-950/45 sm:via-transparent sm:to-neutral-950/35"></div>
                        <div class="relative z-10 grid min-h-[540px] items-end gap-8 px-4 pt-8 pb-20 sm:min-h-[520px] sm:px-8 sm:py-8 lg:min-h-[560px] lg:grid-cols-[minmax(0,1fr)_320px] lg:items-center lg:px-12">
                            <div class="max-w-2xl">
                                <div class="mb-3 flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase text-neutral-200 sm:mb-4 sm:text-xs">
                                    <span class="rounded-full border border-red-500/30 bg-red-500/15 px-2.5 py-1 text-red-200 sm:px-3">Ongoing Teratas</span>
                                    @if($rating)
                                    <span class="inline-flex items-center gap-1 rounded-full border border-yellow-400/30 bg-yellow-400/10 px-2.5 py-1 text-yellow-300 sm:px-3">
                                        <i class="ri-star-fill"></i> {{ $rating }}
                                    </span>
                                    @endif
                                    @if(!empty($anime['releaseDay']))
                                    <span class="rounded-full border border-white/10 bg-white/10 px-2.5 py-1 text-neutral-200 sm:px-3">{{ $anime['releaseDay'] }}</span>
                                    @endif
                                </div>
                                <p class="font-display text-4xl text-red-300 sm:text-7xl">Prasunk Anime</p>
                                <h1 class="mt-3 text-2xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">{{ $anime['title'] }}</h1>
                                <p class="mt-4 max-w-xl text-xs leading-relaxed text-neutral-300 sm:text-base">
                                    Anime ongoing dengan skor tertinggi dari koleksi terbaru. Tonton update episode subtitle Indonesia dengan tampilan yang nyaman.
                                </p>
                                <div class="mt-6 flex flex-wrap gap-3 sm:mt-7">
                                    <a href="{{ $watchUrl }}" class="inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-3 text-xs font-bold text-white shadow-lg shadow-red-950/30 transition hover:bg-red-500 sm:px-5 sm:text-sm">
                                        <i class="ri-play-fill text-base sm:text-lg"></i> Tonton Sekarang
                                    </a>
                                    <a href="{{ route('anime.detail', $slug) }}" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-3 text-xs font-semibold text-white backdrop-blur transition hover:border-white/25 hover:bg-white/15 sm:px-5 sm:text-sm">
                                        <i class="ri-information-line text-base sm:text-lg"></i> Detail
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('anime.detail', $slug) }}" class="group hidden overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] p-3 shadow-2xl shadow-black/30 lg:block">
                                <div class="aspect-[3/4] overflow-hidden rounded-xl bg-neutral-900">
                                    <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                </div>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="relative min-h-[520px] bg-neutral-950 px-5 py-10 sm:px-8 lg:px-12">
                        <div class="flex min-h-[460px] max-w-2xl flex-col justify-center">
                            <p class="font-display text-6xl text-red-300 sm:text-7xl">Streaming Anime Gratis</p>
                            <h1 class="mt-3 text-4xl font-extrabold text-white sm:text-5xl">Daftar Anime <span class="text-red-500">Sub Indo</span></h1>
                            <p class="mt-4 text-base leading-relaxed text-neutral-400">Nonton streaming anime subtitle Indonesia gratis dan terupdate setiap hari.</p>
                        </div>
                    </div>
                @endforelse

                @if(count($heroAnime) > 1)
                <div class="absolute bottom-7 left-4 z-20 flex gap-2 sm:bottom-5 sm:left-auto sm:right-8 lg:right-12">
                    @foreach ($heroAnime as $index => $anime)
                    <button type="button" class="hero-dot h-2.5 rounded-full transition-all {{ $index === 0 ? 'w-8 bg-red-500' : 'w-2.5 bg-white/35 hover:bg-white/60' }}" data-hero-dot="{{ $index }}" aria-label="Slide anime {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                @endif
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
        <div class="relative">
            <div id="ongoingLoading" class="pointer-events-none absolute inset-0 z-20 hidden items-center justify-center rounded-2xl bg-neutral-950/45 backdrop-blur-[2px]">
                <div class="flex items-center gap-3 rounded-full border border-white/10 bg-neutral-950/90 px-4 py-2 text-sm font-medium text-neutral-200 shadow-2xl">
                    <span class="h-4 w-4 animate-spin rounded-full border-2 border-red-500/30 border-t-red-500"></span>
                    Memuat anime...
                </div>
            </div>

            <div id="ongoingGrid" class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 transition-opacity duration-300">
                @foreach ($ongoingAnime as $anime)
                @php $slug = $anime['animeId'] ?? ''; @endphp
                @continue(empty($slug))
                <a href="{{ route('anime.detail', $slug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                    <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                        <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                        @if(!empty($anime['episodes']))
                        <div class="absolute top-2 right-2 rounded-full border border-red-500/20 bg-red-600/90 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                            Ep {{ $anime['episodes'] }}
                        </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] }}</h3>
                        @php $rating = $anime['score'] ?? $anime['rating'] ?? ''; @endphp
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                            <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                                <i class="ri-calendar-line text-neutral-400"></i> {{ $anime['releaseDay'] ?? 'Ongoing' }}
                            </span>
                            @if($rating)
                            <span class="inline-flex items-center gap-1 text-yellow-400 font-medium">
                                <i class="ri-star-fill text-[10px]"></i> {{ $rating }}
                            </span>
                            @elseif(!empty($anime['latestReleaseDate']))
                            <span class="text-neutral-400">{{ $anime['latestReleaseDate'] }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
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
        <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach (array_slice($homeData['completed']['animeList'], 0, 10) as $anime)
            @php $slug = $anime['animeId'] ?? ''; @endphp
            @continue(empty($slug))
            <a href="{{ route('anime.detail', $slug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                    <img src="{{ $anime['poster'] ?? '' }}" alt="{{ $anime['title'] }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    @if(!empty($anime['episodes']))
                    <div class="absolute top-2 right-2 rounded-full border border-white/10 bg-neutral-900/85 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                        {{ $anime['episodes'] }} Eps
                    </div>
                    @endif
                </div>
                <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                    <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] }}</h3>
                    <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                        <span class="inline-flex items-center gap-1 rounded-full border border-green-500/20 px-2 py-0.5 sm:px-2.5 sm:py-1 text-green-400">
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
            const heroSlider = document.getElementById('animeHeroSlider');
            const heroSlides = heroSlider ? Array.from(heroSlider.querySelectorAll('[data-hero-slide]')) : [];
            const heroDots = heroSlider ? Array.from(heroSlider.querySelectorAll('[data-hero-dot]')) : [];
            let activeHeroSlide = 0;
            let heroTimer = null;

            const showHeroSlide = (nextIndex) => {
                if (heroSlides.length <= 1) return;
                activeHeroSlide = (nextIndex + heroSlides.length) % heroSlides.length;
                heroSlides.forEach((slide, index) => {
                    const active = index === activeHeroSlide;
                    slide.classList.toggle('is-active', active);
                    slide.classList.toggle('opacity-100', active);
                    slide.classList.toggle('opacity-0', !active);
                });
                heroDots.forEach((dot, index) => {
                    const active = index === activeHeroSlide;
                    dot.classList.toggle('w-8', active);
                    dot.classList.toggle('w-2.5', !active);
                    dot.classList.toggle('bg-red-500', active);
                    dot.classList.toggle('bg-white/35', !active);
                });
            };

            const startHeroSlider = () => {
                if (heroSlides.length <= 1) return;
                window.clearInterval(heroTimer);
                heroTimer = window.setInterval(() => showHeroSlide(activeHeroSlide + 1), 6500);
            };

            heroDots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    showHeroSlide(Number(dot.dataset.heroDot || 0));
                    startHeroSlider();
                });
            });
            startHeroSlider();

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
                        fetch(`/anime/search-ajax?q=${encodeURIComponent(query)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        })
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
            const loading = document.getElementById('ongoingLoading');
            if (!grid || !nav) return;
            if (nav.dataset.loading === 'true') return;

            nav.dataset.loading = 'true';
            grid.classList.add('opacity-35', 'pointer-events-none');
            loading?.classList.remove('hidden');
            loading?.classList.add('flex');
            nav.querySelectorAll('button').forEach(button => {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-wait');
            });
            
            fetch(`/anime/ongoing-ajax?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
                .then(res => res.json())
                .then(res => {
                    if(res.data && res.data.length > 0) {
                        let html = '';
                        res.data.forEach(anime => {
                            const slug = anime.animeId || anime.slug || '';
                            if(!slug) return;
                            
                            html += `
                                <a href="/anime/anime/${slug}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                                    <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                                        <img src="${anime.poster || ''}" alt="${anime.title || ''}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                        ${anime.episodes ? `
                                        <div class="absolute top-2 right-2 rounded-full border border-red-500/20 bg-red-600/90 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                                            Ep ${anime.episodes}
                                        </div>` : ''}
                                    </div>
                                    <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">${anime.title || ''}</h3>
                                        <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                                            <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                                                <i class="ri-calendar-line text-neutral-400"></i> ${anime.releaseDay || 'Ongoing'}
                                            </span>
                                            ${(anime.score || anime.rating)
                                                ? `<span class="inline-flex items-center gap-1 text-yellow-400 font-medium"><i class="ri-star-fill text-[10px]"></i> ${anime.score || anime.rating}</span>`
                                                : (anime.latestReleaseDate ? `<span class="text-neutral-400">${anime.latestReleaseDate}</span>` : '')
                                            }
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
                })
                .catch(err => {
                    console.error('Failed to load pagination:', err);
                })
                .finally(() => {
                    nav.dataset.loading = 'false';
                    grid.classList.remove('opacity-35', 'pointer-events-none');
                    loading?.classList.add('hidden');
                    loading?.classList.remove('flex');
                    nav.querySelectorAll('button').forEach(button => {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-wait');
                    });
                });
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
        .anime-hero-slide.is-active .anime-hero-bg {
            transform: scale(1.08);
        }
    </style>
    @endpush
</x-layout.app>
