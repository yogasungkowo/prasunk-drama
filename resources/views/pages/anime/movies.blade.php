<x-layout.app title="Koleksi Movie Anime - Prasunk Anime" description="Daftar lengkap movie anime subtitle Indonesia.">

    <section class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Beranda Anime
            </a>
        </div>

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="font-display text-5xl text-red-400">Film Anime</p>
                <h1 class="mt-2 text-3xl font-extrabold text-white flex items-center gap-3">
                    <i class="ri-movie-2-line text-red-500"></i> Koleksi <span class="text-red-500">Movie Anime</span>
                </h1>
                <p class="text-sm text-neutral-400 mt-2">Daftar lengkap movie anime subtitle Indonesia.</p>
            </div>
            <form id="moviesSearchForm" method="GET" action="{{ route('anime.movies') }}" class="w-full md:w-80" onsubmit="event.preventDefault(); loadMoviesPage(1);">
                <div class="relative w-full">
                    <input type="text" id="moviesSearchInput" name="q" value="{{ request('q') }}" placeholder="Cari judul movie..." class="w-full rounded-full border border-white/10 bg-white/[0.03] px-5 py-3 pr-12 text-sm text-white placeholder-neutral-500 transition focus:border-red-500/50 focus:bg-white/[0.05] focus:outline-none">
                    <button type="submit" class="absolute right-2 top-1.5 bottom-1.5 rounded-full bg-red-600 px-3 flex items-center justify-center text-white transition hover:bg-red-500">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="relative">
            <div id="moviesLoading" class="pointer-events-none absolute inset-0 z-20 hidden items-center justify-center rounded-2xl bg-neutral-950/45 backdrop-blur-[2px]">
                <div class="flex items-center gap-3 rounded-full border border-white/10 bg-neutral-950/90 px-4 py-2 text-sm font-medium text-neutral-200 shadow-2xl">
                    <span class="h-4 w-4 animate-spin rounded-full border-2 border-red-500/30 border-t-red-500"></span>
                    Memuat movie...
                </div>
            </div>

            <div id="moviesGrid" class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 transition-opacity duration-300">
                @if(!empty($movieList))
                @foreach ($movieList as $anime)
                @php $slug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? ''; @endphp
                @if($slug)
                <a href="{{ route('anime.movies.detail', $slug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                    <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                        <img src="{{ $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '' }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                        
                        <div class="absolute top-2 right-2 rounded-full border border-red-500/20 bg-red-600/90 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                            Movie
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] ?? '' }}</h3>
                        @php $rating = $anime['score'] ?? $anime['rating'] ?? ''; @endphp
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                            <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                                <i class="ri-movie-line text-neutral-400"></i> {{ $anime['status'] ?? 'Movie' }}
                            </span>
                            @if($rating)
                            <span class="inline-flex items-center gap-1 text-yellow-400 font-medium">
                                <i class="ri-star-fill text-[10px]"></i> {{ $rating }}
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @endforeach
                @endif
            </div>
            
            <div id="moviesEmptyState" class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01] {{ empty($movieList) ? 'block' : 'hidden' }}">
                <i class="ri-movie-2-line text-5xl text-neutral-700 mb-4 block"></i>
                <h3 class="text-lg font-semibold text-white">Tidak ada movie ditemukan</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba beberapa saat lagi atau cari judul lain.</p>
            </div>
        </div>
        
        @php
            $currentPage = $currentPage ?? 1;
            $prevPage = max(1, $currentPage - 1);
            $nextPage = $currentPage + 1;
            $searchQuery = request('q');
        @endphp
        
        <nav id="moviesPagination" class="flex items-center justify-center gap-2 mt-10 {{ empty($movieList) && empty($searchQuery) ? 'hidden' : '' }}" data-current-page="{{ $currentPage }}">
            <button onclick="loadMoviesPage({{ $prevPage }})" class="btn-prev {{ $currentPage <= 1 ? 'hidden' : 'inline-flex' }} h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
            <span class="page-indicator inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-600 text-sm font-semibold text-white shadow-lg shadow-red-600/30">{{ $currentPage }}</span>
            <button onclick="loadMoviesPage({{ $nextPage }})" class="btn-next inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </button>
        </nav>
    </section>

    @push('scripts')
    <script>
        // Use debounce for search input if desired
        let searchTimeout = null;
        const searchInput = document.getElementById('moviesSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadMoviesPage(1);
                }, 500);
            });
        }

        function loadMoviesPage(page) {
            const grid = document.getElementById('moviesGrid');
            const nav = document.getElementById('moviesPagination');
            const loading = document.getElementById('moviesLoading');
            const emptyState = document.getElementById('moviesEmptyState');
            const query = searchInput ? searchInput.value.trim() : '';
            
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
            
            const url = new URL(window.location.origin + '/anime/movies');
            url.searchParams.set('page', page);
            if (query) url.searchParams.set('q', query);

            fetch(url.toString(), {
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
                            const slug = anime.animeId || anime.slug || anime.id || '';
                            if(!slug) return;
                            
                            html += `
                                <a href="/anime/movies/${slug}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                                    <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                                        <img src="${anime.poster || anime.cover || anime.thumbnail || ''}" alt="${anime.title || ''}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                        <div class="absolute top-2 right-2 rounded-full border border-red-500/20 bg-red-600/90 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                                            Movie
                                        </div>
                                    </div>
                                    <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">${anime.title || ''}</h3>
                                        <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                                            <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                                                <i class="ri-movie-line text-neutral-400"></i> ${anime.status || 'Movie'}
                                            </span>
                                            ${anime.score ? `<span class="inline-flex items-center gap-1 text-yellow-400 font-medium"><i class="ri-star-fill text-[10px]"></i> ${anime.score}</span>` : ''}
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        grid.innerHTML = html;
                        emptyState?.classList.add('hidden');
                        emptyState?.classList.remove('block');
                        grid.classList.remove('hidden');
                        nav.classList.remove('hidden');
                    } else {
                        grid.innerHTML = '';
                        grid.classList.add('hidden');
                        emptyState?.classList.remove('hidden');
                        emptyState?.classList.add('block');
                    }

                    // Update Pagination
                    const currentPage = parseInt(res.currentPage) || 1;
                    nav.dataset.currentPage = currentPage;
                    const prevBtn = nav.querySelector('.btn-prev');
                    const nextBtn = nav.querySelector('.btn-next');
                    const indicator = nav.querySelector('.page-indicator');
                    
                    if (indicator) indicator.textContent = currentPage;
                    
                    if (prevBtn) {
                        prevBtn.onclick = () => loadMoviesPage(currentPage - 1);
                        if (currentPage <= 1) {
                            prevBtn.classList.add('hidden');
                            prevBtn.classList.remove('inline-flex');
                        } else {
                            prevBtn.classList.remove('hidden');
                            prevBtn.classList.add('inline-flex');
                        }
                    }
                    
                    if (nextBtn) {
                        nextBtn.onclick = () => loadMoviesPage(currentPage + 1);
                    }
                    
                    // Update URL silently
                    window.history.replaceState({}, '', url.toString());
                })
                .catch(err => {
                    console.error('Failed to load movies:', err);
                })
                .finally(() => {
                    grid.classList.remove('opacity-35', 'pointer-events-none');
                    loading?.classList.add('hidden');
                    loading?.classList.remove('flex');
                    nav.dataset.loading = 'false';
                    nav.querySelectorAll('button').forEach(button => {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-wait');
                    });
                });
        }
    </script>
    @endpush

</x-layout.app>
