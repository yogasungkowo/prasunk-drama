<x-layout.app title="Semua Anime - Prasunk Anime" description="Daftar semua anime subtitle Indonesia lengkap.">

    <section class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Kembali ke Beranda Anime
            </a>
        </div>

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-display text-5xl text-red-400">Katalog Lengkap</p>
                <h1 class="mt-2 text-3xl font-extrabold text-white flex items-center gap-3">
                    <i class="ri-book-open-fill text-red-500"></i> Semua <span class="text-red-500">Anime</span>
                </h1>
                <p class="text-sm text-neutral-400 mt-2">Koleksi lengkap anime subtitle Indonesia dengan filter judul, genre, status, tipe, dan rating.</p>
            </div>

            <div id="animeUnlimitedSummary" class="text-sm text-neutral-400 {{ $animeList->total() > 0 ? '' : 'hidden' }}">
                @if($animeList->total() > 0)
                Menampilkan <span class="font-semibold text-white">{{ $animeList->firstItem() }}</span>-<span class="font-semibold text-white">{{ $animeList->lastItem() }}</span>
                dari <span class="font-semibold text-white">{{ $animeList->total() }}</span> anime
                @endif
            </div>
        </div>

        <form id="animeUnlimitedFilterForm" method="GET" action="{{ route('anime.unlimited') }}" class="mb-8 rounded-2xl border border-white/5 bg-white/[0.02] p-4 sm:p-5">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-6">
                <div class="lg:col-span-2">
                    <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-neutral-500">Cari Judul</label>
                    <div class="relative">
                        <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" type="text" placeholder="Masukkan judul anime..." class="h-11 w-full rounded-xl border border-white/10 bg-neutral-950/60 px-4 pr-10 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-red-500/50">
                        <i class="ri-search-line absolute right-4 top-3 text-neutral-500"></i>
                    </div>
                </div>

                <div>
                    <label for="sort" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-neutral-500">Urutkan</label>
                    <select id="sort" name="sort" data-placeholder="Urutkan anime" class="anime-filter-select h-11 w-full rounded-xl border border-white/10 bg-neutral-950/60 px-3 text-sm text-white outline-none transition focus:border-red-500/50">
                        <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>New - Old</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Old - New</option>
                        <option value="az" @selected(($filters['sort'] ?? '') === 'az')>A - Z</option>
                        <option value="za" @selected(($filters['sort'] ?? '') === 'za')>Z - A</option>
                        <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Rating</option>
                    </select>
                </div>

                <div>
                    <label for="genre" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-neutral-500">Genre</label>
                    <select id="genre" name="genre" data-placeholder="Semua Genre" class="anime-filter-select h-11 w-full rounded-xl border border-white/10 bg-neutral-950/60 px-3 text-sm text-white outline-none transition focus:border-red-500/50">
                        <option value="">Semua Genre</option>
                        @foreach(($filterOptions['genres'] ?? []) as $genreSlug => $genreTitle)
                        <option value="{{ $genreSlug }}" @selected(($filters['genre'] ?? '') === $genreSlug)>{{ $genreTitle }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-neutral-500">Status</label>
                    <select id="status" name="status" data-placeholder="Semua Status" class="anime-filter-select h-11 w-full rounded-xl border border-white/10 bg-neutral-950/60 px-3 text-sm text-white outline-none transition focus:border-red-500/50">
                        <option value="">Semua Status</option>
                        @foreach(($filterOptions['statuses'] ?? []) as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-neutral-500">Tipe</label>
                    <select id="type" name="type" data-placeholder="Semua Tipe" class="anime-filter-select h-11 w-full rounded-xl border border-white/10 bg-neutral-950/60 px-3 text-sm text-white outline-none transition focus:border-red-500/50">
                        <option value="">Semua Tipe</option>
                        @foreach(($filterOptions['types'] ?? []) as $type)
                        <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <a href="{{ route('anime.unlimited') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-white/10 px-5 text-sm font-medium text-neutral-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                    <i class="ri-refresh-line"></i> Reset
                </a>
            </div>
        </form>

        <div class="relative" id="animeUnlimitedResultsShell">
            <div id="animeUnlimitedLoading" class="pointer-events-none absolute inset-0 z-20 hidden items-start justify-center rounded-2xl bg-neutral-950/45 pt-20 backdrop-blur-[2px]">
                <div class="flex items-center gap-3 rounded-full border border-white/10 bg-neutral-950/90 px-4 py-2 text-sm font-medium text-neutral-200 shadow-2xl">
                    <span class="h-4 w-4 animate-spin rounded-full border-2 border-red-500/30 border-t-red-500"></span>
                    Memuat anime...
                </div>
            </div>
            <div id="animeUnlimitedResults" class="transition-opacity duration-300">
                @include('pages.anime.partials.unlimited-results', ['animeList' => $animeList])
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('animeUnlimitedFilterForm');
            const results = document.getElementById('animeUnlimitedResults');
            const summary = document.getElementById('animeUnlimitedSummary');
            const loading = document.getElementById('animeUnlimitedLoading');
            const searchInput = document.getElementById('q');
            const selects = document.querySelectorAll('.anime-filter-select');
            const resetLink = form?.querySelector('a[href="{{ route('anime.unlimited') }}"]');
            let searchTimer = null;
            let requestController = null;

            const setLoading = (isLoading) => {
                if (!results || !loading) return;

                loading.classList.toggle('hidden', !isLoading);
                loading.classList.toggle('flex', isLoading);
                results.classList.toggle('opacity-35', isLoading);
                results.classList.toggle('pointer-events-none', isLoading);
            };

            const buildUrl = (pageUrl = null) => {
                const url = pageUrl ? new URL(pageUrl, window.location.origin) : new URL(form.action);
                const params = new URLSearchParams(new FormData(form));

                if (!pageUrl) {
                    params.delete('page');
                } else if (url.searchParams.get('page')) {
                    params.set('page', url.searchParams.get('page'));
                }

                [...params.entries()].forEach(([key, value]) => {
                    if (value === '' || value === null) {
                        params.delete(key);
                    }
                });

                url.search = params.toString();
                return url;
            };

            const loadAnime = (pageUrl = null, pushState = true) => {
                if (!form || !results) return;

                const url = buildUrl(pageUrl);
                requestController?.abort();
                requestController = new AbortController();
                setLoading(true);

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    signal: requestController.signal,
                })
                    .then((response) => {
                        if (!response.ok) throw new Error('Gagal memuat anime.');
                        return response.json();
                    })
                    .then((payload) => {
                        results.innerHTML = payload.html || '';

                        if (summary) {
                            summary.innerHTML = payload.summary || '';
                            summary.classList.toggle('hidden', !payload.total);
                        }

                        if (pushState) {
                            window.history.pushState({}, '', url.toString());
                        }
                    })
                    .catch((error) => {
                        if (error.name !== 'AbortError') {
                            console.error('Anime filter error:', error);
                        }
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            };

            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                window.jQuery(selects).select2({
                    width: '100%',
                    dropdownAutoWidth: false,
                    placeholder: function() {
                        return window.jQuery(this).data('placeholder') || 'Pilih filter';
                    }
                });

                window.jQuery(selects).on('change', () => loadAnime());
            } else {
                selects.forEach((select) => {
                    select.addEventListener('change', () => loadAnime());
                });
            }

            searchInput?.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => loadAnime(), 350);
            });

            form?.addEventListener('submit', (event) => {
                event.preventDefault();
                loadAnime();
            });

            resetLink?.addEventListener('click', (event) => {
                event.preventDefault();
                if (form.elements.q) form.elements.q.value = '';
                if (form.elements.sort) form.elements.sort.value = 'newest';
                if (form.elements.genre) form.elements.genre.value = '';
                if (form.elements.status) form.elements.status.value = '';
                if (form.elements.type) form.elements.type.value = '';

                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                    window.jQuery('#genre, #status, #type').val('').trigger('change.select2');
                    window.jQuery('#sort').val('newest').trigger('change.select2');
                }

                loadAnime(resetLink.href);
            });

            results?.addEventListener('click', (event) => {
                const link = event.target.closest('[data-anime-pagination] a');
                if (!link) return;

                event.preventDefault();
                loadAnime(link.href);
                document.getElementById('animeUnlimitedResultsShell')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            });

            window.addEventListener('popstate', () => {
                const params = new URLSearchParams(window.location.search);
                ['q', 'sort', 'genre', 'status', 'type'].forEach((name) => {
                    const field = form?.elements[name];
                    if (!field) return;
                    field.value = params.get(name) || (name === 'sort' ? 'newest' : '');
                });

                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
                    window.jQuery(selects).trigger('change.select2');
                }

                loadAnime(window.location.href, false);
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            background: rgba(10, 10, 10, 0.6);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgb(245, 245, 245);
            line-height: 42px;
            padding-left: 0.75rem;
            padding-right: 2rem;
            font-size: 0.875rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgb(115, 115, 115);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 0.5rem;
        }

        .select2-dropdown {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            background: rgb(23, 23, 23);
            color: rgb(245, 245, 245);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.625rem;
            background: rgb(10, 10, 10);
            color: rgb(245, 245, 245);
            outline: none;
        }

        .select2-container--default .select2-results__option--selected {
            background: rgba(220, 38, 38, 0.25);
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background: rgb(220, 38, 38);
            color: white;
        }
    </style>
    @endpush

</x-layout.app>
