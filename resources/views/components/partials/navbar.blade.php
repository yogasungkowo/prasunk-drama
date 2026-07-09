@php
    $isAnimePage = request()->routeIs('anime.*') || request()->is('anime*');
@endphp

<header class="sticky top-0 z-50 border-b border-white/5 bg-neutral-950/80 backdrop-blur-xl">
    <nav class="mx-auto flex w-full max-w-7xl items-center justify-between gap-2 px-3 py-2.5 sm:gap-4 sm:px-6 sm:py-4 lg:px-8">
        <a href="{{ $isAnimePage ? route('anime.index') : '/' }}" class="shrink-0 text-xl font-bold tracking-tight text-white transition hover:text-red-300 sm:text-3xl">
            Prasunk<span class="font-display text-2xl font-normal text-red-400 sm:text-4xl">{{ $isAnimePage ? 'Anime' : 'Drama' }}</span>
        </a>

        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 sm:gap-4">
            {{-- Drama / Anime Toggle --}}
            <div class="flex items-center gap-1 rounded-full border border-white/10 bg-white/[0.02] p-0.5 shrink-0 whitespace-nowrap">
                <a href="/" class="rounded-full px-2 py-1 text-[9px] font-medium transition sm:px-3 sm:py-1.5 sm:text-xs {{ request()->is('anime*') ? 'text-neutral-400 hover:text-white' : 'bg-red-950/50 text-red-400 border border-red-900/30' }}">
                    Drama
                </a>
                <a href="{{ route('anime.index') }}" class="rounded-full px-2 py-1 text-[9px] font-medium transition sm:px-3 sm:py-1.5 sm:text-xs {{ request()->is('anime*') ? 'bg-red-950/50 text-red-400 border border-red-900/30' : 'text-neutral-400 hover:text-white' }}">
                    Anime
                </a>
            </div>

            @if(request()->is('anime*'))
            <div class="relative hidden sm:block w-48 md:w-64" id="navAnimeSearchContainer">
                <input type="text" id="navAnimeSearchInput" autocomplete="off" placeholder="Cari anime..." class="w-full bg-white/[0.02] border border-white/10 rounded-full px-4 py-2 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-red-500/50 focus:bg-white/[0.05] transition">
                <div class="absolute right-4 top-2 text-neutral-400 pointer-events-none">
                    <i class="ri-search-line"></i>
                </div>
                <div id="navAnimeSuggestionBox" class="absolute right-0 mt-2 w-72 md:w-[350px] z-50 hidden rounded-2xl border border-white/10 bg-neutral-900/98 p-2 shadow-2xl backdrop-blur-xl max-h-[350px] overflow-y-auto no-scrollbar"></div>
            </div>
            
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const initAnimeNavSearch = (inputId, boxId, containerId) => {
                        const navSearchInput = document.getElementById(inputId);
                        const navSuggestionBox = document.getElementById(boxId);
                        let navSearchTimeout = null;

                        if (!navSearchInput || !navSuggestionBox) return;

                        navSearchInput.addEventListener('input', function() {
                            const query = this.value.trim();
                            clearTimeout(navSearchTimeout);

                            if (query.length < 2) {
                                navSuggestionBox.classList.add('hidden');
                                return;
                            }

                            navSearchTimeout = setTimeout(() => {
                                fetch(`/anime/search-ajax?q=${encodeURIComponent(query)}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                    },
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.length === 0) {
                                            navSuggestionBox.innerHTML = '<div class="p-4 text-sm text-neutral-400 text-center"><i class="ri-search-line text-xl opacity-50 block mb-1"></i>Tidak ada hasil</div>';
                                            navSuggestionBox.classList.remove('hidden');
                                            return;
                                        }

                                        let html = '';
                                        data.forEach(item => {
                                            const slug = item.animeId || item.slug || item.id || '';
                                            const rating = item.score || item.rating || '';
                                            html += `
                                                <a href="/anime/anime/${slug}" onclick="window.location.href='/anime/anime/${slug}'; return false;" class="flex items-center gap-3 p-2 rounded-xl transition hover:bg-white/[0.06] group relative z-50 cursor-pointer pointer-events-auto">
                                                    <div class="w-10 h-14 rounded-lg overflow-hidden bg-neutral-950 shrink-0 pointer-events-none">
                                                        <img src="${item.poster || item.cover || item.thumbnail || ''}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                                    </div>
                                                    <div class="flex-1 min-w-0 pointer-events-none">
                                                        <h4 class="text-xs font-semibold text-white truncate group-hover:text-red-400 transition">${item.title}</h4>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            ${rating ? `<span class="text-[10px] text-yellow-400"><i class="ri-star-fill"></i> ${rating}</span>` : ''}
                                                        </div>
                                                    </div>
                                                </a>
                                            `;
                                        });
                                        navSuggestionBox.innerHTML = html;
                                        navSuggestionBox.classList.remove('hidden');
                                    })
                                    .catch(err => console.error('Nav Anime suggest error:', err));
                            }, 400);
                        });

                        navSearchInput.addEventListener('keydown', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                const query = this.value.trim();
                                if (query.length > 0) {
                                    window.location.href = `/anime/search/${encodeURIComponent(query)}`;
                                }
                            }
                        });

                        document.addEventListener('click', function(e) {
                            const container = document.getElementById(containerId);
                            if (container && !container.contains(e.target)) {
                                navSuggestionBox.classList.add('hidden');
                            }
                        });
                    };

                    initAnimeNavSearch('navAnimeSearchInput', 'navAnimeSuggestionBox', 'navAnimeSearchContainer');
                    initAnimeNavSearch('navAnimeMobileSearchInput', 'navAnimeMobileSuggestionBox', 'navAnimeMobileSearchContainer');
                });
            </script>
            @endpush
            @endif

            @if(!request()->is('anime*'))
            @php
                $iconDirs = glob(public_path('assets/icons/*'));
                $platformIcons = [];
                foreach ($iconDirs as $iconFile) {
                    $filename = pathinfo($iconFile, PATHINFO_FILENAME);
                    $platformIcons[$filename] = asset('assets/icons/' . basename($iconFile));
                }
                $selectedName = $platforms[$selectedSource] ?? null;
            @endphp

            <details class="relative shrink-0">
                <summary class="flex cursor-pointer list-none items-center gap-1.5 sm:gap-2 rounded-full border border-white/10 px-3 sm:px-4 py-1.5 sm:py-2 text-sm font-medium text-neutral-200 transition hover:border-red-500/30 hover:text-white whitespace-nowrap">
                    @if($selectedName && isset($platformIcons[$selectedSource]))
                        <img src="{{ $platformIcons[$selectedSource] }}" alt="{{ $selectedName }}" class="h-4 w-4 sm:h-5 sm:w-5 rounded-sm object-cover flex-shrink-0">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-2.625 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.496 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-2.625 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5c0 .621-.504 1.125-1.125 1.125m1.5 0h12m-12 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m12-3.75c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5m1.5 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-1.5-3.75h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M6 12h12" />
                        </svg>
                    @endif
                    <span class="inline-block">{{ $selectedName ?? 'Pilih Platform' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </summary>
                <div class="absolute right-0 mt-2 w-56 max-h-80 overflow-y-auto no-scrollbar rounded-2xl border border-white/5 bg-neutral-900/95 p-2 text-sm shadow-xl backdrop-blur-xl">
                    @foreach($platforms ?? [] as $key => $name)
                        <a href="/?source={{ $key }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-neutral-300 transition hover:bg-white/5 hover:text-red-300 {{ ($selectedSource ?? '') == $key ? 'text-red-400 font-semibold bg-white/[0.02]' : '' }}">
                            @if(isset($platformIcons[$key]))
                                <img src="{{ $platformIcons[$key] }}" alt="{{ $name }}" class="h-5 w-5 rounded-sm object-cover flex-shrink-0">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400/70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                                </svg>
                            @endif
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
            </details>
            @endif
        </div>
    </nav>
    @if($isAnimePage)
    <div class="border-t border-white/5 px-3 py-2 sm:hidden">
        <div class="relative mx-auto w-full max-w-7xl" id="navAnimeMobileSearchContainer">
            <input type="text" id="navAnimeMobileSearchInput" autocomplete="off" placeholder="Cari anime..." class="w-full rounded-full border border-white/10 bg-white/[0.03] px-4 py-2.5 pr-10 text-sm text-white placeholder-neutral-500 transition focus:border-red-500/50 focus:bg-white/[0.05] focus:outline-none">
            <div class="pointer-events-none absolute right-4 top-2.5 text-neutral-400">
                <i class="ri-search-line"></i>
            </div>
            <div id="navAnimeMobileSuggestionBox" class="absolute left-0 right-0 mt-2 hidden max-h-[320px] overflow-y-auto rounded-2xl border border-white/10 bg-neutral-900/98 p-2 shadow-2xl backdrop-blur-xl no-scrollbar"></div>
        </div>
    </div>
    <div class="border-t border-white/5">
        <div class="mx-auto flex w-full max-w-7xl gap-1.5 overflow-x-auto px-3 py-2 sm:gap-2 sm:px-6 lg:px-8 no-scrollbar">
            <a href="{{ route('anime.unlimited') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.unlimited') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Katalog Anime
            </a>
            <a href="{{ route('anime.ongoing') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.ongoing') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Sedang Tayang
            </a>
            <a href="{{ route('anime.complete') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.complete') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Anime Tamat
            </a>
            <a href="{{ route('anime.genre') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.genre') || request()->routeIs('anime.genre.list') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Genre
            </a>
            <a href="{{ route('anime.schedule') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.schedule') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Jadwal Rilis
            </a>
            <a href="{{ route('anime.movies') }}" class="shrink-0 rounded-full border px-3 py-1.5 text-[11px] font-medium leading-none transition sm:px-4 sm:text-xs {{ request()->routeIs('anime.movies*') ? 'border-red-500/40 bg-red-500/15 text-red-200' : 'border-white/10 bg-white/[0.03] text-neutral-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-200' }}">
                Movie
            </a>
        </div>
    </div>
    @endif
</header>
