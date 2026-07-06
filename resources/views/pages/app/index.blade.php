<x-layout.app title="Prasunk Drama - Daftar Drama" :platforms="$platforms" :selectedSource="$selectedSource">

    {{-- Hero Section --}}
    <section class="mx-auto w-full max-w-7xl px-6 pt-12 pb-12 lg:px-8 lg:pt-14">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div class="max-w-2xl">
                <p class="font-display text-7xl text-red-400">Streaming Drama Gratis</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Daftar Drama <span class="text-red-500">Tersedia</span></h1>
                <p class="mt-4 text-base leading-relaxed text-neutral-400">
                    Nonton short drama terpopuler dari platform <span class="text-red-400 font-semibold">{{ $platforms[$selectedSource] ?? $selectedSource }}</span>. Gratis & terupdate.
                </p>
            </div>

            {{-- Search Bar with Suggestion Overlay --}}
            <div class="w-full md:max-w-xs relative" id="searchContainer">
                <div class="relative">
                    <input type="text" id="searchInput" autocomplete="off" placeholder="Cari drama di {{ $platforms[$selectedSource] }}..." class="w-full bg-white/[0.02] border border-white/10 rounded-full px-5 py-2.5 text-sm text-white placeholder-neutral-500 focus:outline-none focus:border-red-500/50 transition">
                    <div class="absolute right-4 top-3 text-neutral-400">
                        <i class="ri-search-line"></i>
                    </div>
                </div>

                {{-- Suggestion Dropdown Overlay --}}
                <div id="suggestionBox" class="absolute left-0 right-0 mt-2 z-50 hidden rounded-2xl border border-white/5 bg-neutral-900/98 p-2 shadow-2xl backdrop-blur-xl max-h-[380px] overflow-y-auto no-scrollbar">
                    {{-- Dynamically populated search items --}}
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
            <span class="rounded-full border border-red-500/20 bg-red-500/10 px-4 py-1.5 text-xs font-medium text-red-300">
                {{ $dramas->total() }} Drama Terbaru Dari {{ $platforms[$selectedSource] ?? $selectedSource }}
            </span>
            <div class="flex items-center gap-2">
                <span class="text-xs text-neutral-500 font-medium mr-1">Bagikan:</span>
                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Prasunk Drama - Streaming Short Drama Terpopuler: Nonton short drama terpopuler secara gratis dengan subtitle Indonesia! ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-green-500/20 hover:border-green-500/30 text-neutral-300 hover:text-green-400 transition" title="Bagikan ke WhatsApp">
                    <i class="ri-whatsapp-line text-sm"></i>
                </a>
                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-blue-600/20 hover:border-blue-600/30 text-neutral-300 hover:text-blue-400 transition" title="Bagikan ke Facebook">
                    <i class="ri-facebook-fill text-sm"></i>
                </a>
                <!-- X / Twitter -->
                <a href="https://twitter.com/intent/tweet?text={{ rawurlencode('Nonton short drama terpopuler secara gratis dengan subtitle Indonesia! @PrasunkDrama') }}&url={{ rawurlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-neutral-100/10 hover:border-neutral-100/20 text-neutral-300 hover:text-white transition" title="Bagikan ke X">
                    <i class="ri-twitter-x-fill text-xs"></i>
                </a>
                <!-- Web Share / Copy Link -->
                <button onclick="sharePage('{{ rawurlencode('Prasunk Drama - Streaming Short Drama Terpopuler') }}', '{{ rawurlencode('Nonton short drama terpopuler secara gratis dengan subtitle Indonesia!') }}', '{{ request()->url() }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/10 bg-white/[0.02] hover:bg-white/[0.06] text-neutral-300 hover:text-white transition cursor-pointer" title="Salin Tautan">
                    <i class="ri-link text-sm"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- Trending Swiper --}}
    @if(!empty($trendingDramas))
    <section class="mx-auto w-full max-w-7xl px-6 pb-6 lg:px-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="ri-fire-fill text-orange-500"></i> Trending di <span class="text-red-400">{{ $platforms[$selectedSource] ?? $selectedSource }}</span>
            </h2>
            <div class="flex items-center gap-2">
                <button class="trending-prev flex h-8 w-8 items-center justify-center rounded-full border border-white/10 text-neutral-400 hover:text-white transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                </button>
                <button class="trending-next flex h-8 w-8 items-center justify-center rounded-full border border-white/10 text-neutral-400 hover:text-white transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                </button>
            </div>
        </div>
        <div class="swiper trending-swiper overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($trendingDramas as $drama)
                <div class="swiper-slide !w-[180px] sm:!w-[200px]">
                    <article onclick="openDramaModal('{{ $drama['id'] }}', '{{ $drama['source'] }}')" class="group cursor-pointer rounded-2xl border border-white/5 bg-white/[0.02] p-3 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                        <div class="mb-3 aspect-[3/4] w-full rounded-xl overflow-hidden bg-neutral-900">
                            <img src="{{ $drama['cover'] }}" alt="{{ $drama['title'] }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                        </div>
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-semibold text-white truncate">{{ $drama['title'] }}</h3>
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="rounded-full border border-white/10 px-2 py-0.5 text-neutral-300">{{ $drama['source_name'] }}</span>
                                <span class="text-red-300"><i class="ri-star-fill text-[10px]"></i> {{ $drama['rating'] }}</span>
                            </div>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Section Pemisah --}}
    <section class="mx-auto w-full max-w-7xl px-6 pb-4 lg:px-8">
        <div class="border-t border-white/5 pt-8">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="ri-tv-2-line text-blue-400"></i> Terbaru dari <span class="text-red-400">{{ $platforms[$selectedSource] ?? $selectedSource }}</span>
            </h2>
            <p class="text-sm text-neutral-500 mt-1">Jelajahi drama terbaru dan temukan tontonan favoritmu</p>
        </div>
    </section>

    {{-- Drama Grid --}}
    <section id="daftar-drama" class="mx-auto w-full max-w-7xl px-6 pb-8 lg:px-8">
        @if($dramas->isEmpty())
            <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-neutral-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <h3 class="text-lg font-semibold text-white">Tidak ada drama ditemukan</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba ganti kata kunci pencarian atau ganti platform source.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($dramas->take(12) as $drama)
                    <article onclick="openDramaModal('{{ $drama['id'] }}', '{{ $drama['source'] }}')" class="group cursor-pointer rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                        <div class="mb-3 sm:mb-4 aspect-[3/4] w-full rounded-xl overflow-hidden bg-neutral-900">
                            <img src="{{ $drama['cover'] }}" alt="{{ $drama['title'] }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                        </div>
                        <div class="space-y-1.5 sm:space-y-2">
                            <h2 class="text-sm sm:text-base font-semibold text-white truncate">{{ $drama['title'] }}</h2>
                            <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                                <span class="rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">{{ $drama['source_name'] }}</span>
                                <span class="text-red-300"><i class="ri-star-fill text-[10px]"></i> {{ $drama['rating'] }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Pagination --}}
    @if ($dramas->hasPages())
        <section class="mx-auto w-full max-w-7xl px-6 pb-16 lg:px-8">
            <nav class="flex items-center justify-center gap-2">
                {{-- Previous --}}
                @if ($dramas->onFirstPage())
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 text-neutral-600 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $dramas->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($dramas->getUrlRange(max(1, $dramas->currentPage() - 2), min($dramas->lastPage(), $dramas->currentPage() + 2)) as $page => $url)
                    @if ($page == $dramas->currentPage())
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-600 text-sm font-semibold text-white shadow-lg shadow-red-600/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-sm text-neutral-400 transition hover:border-red-500/30 hover:text-white">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($dramas->hasMorePages())
                    <a href="{{ $dramas->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 text-neutral-600 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                @endif
            </nav>
        </section>
    @endif

    {{-- Modal Detail Drama --}}
    <div id="dramaModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-neutral-950/80 backdrop-blur-md">
        <div class="relative w-full max-w-2xl rounded-3xl border border-white/5 bg-neutral-900 p-6 md:p-8 shadow-2xl overflow-y-auto max-h-[90vh] no-scrollbar">
            <button onclick="closeDramaModal()" class="absolute right-5 top-5 text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div id="modalLoading" class="flex flex-col items-center justify-center py-12">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-red-600/30 border-t-red-600"></div>
                <p class="text-sm text-neutral-500 mt-4">Loading detail drama...</p>
            </div>

            <div id="modalContent" class="hidden">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 aspect-[3/4] rounded-2xl overflow-hidden bg-neutral-950">
                        <img id="modalCover" src="" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <span id="modalPlatform" class="inline-block rounded-full bg-red-950/40 text-red-400 px-3 py-1 text-xs font-semibold border border-red-900/20 mb-2"></span>
                            <h2 id="modalTitle" class="text-2xl font-bold text-white leading-tight"></h2>
                            <p id="modalEpisodes" class="text-xs text-neutral-400 mt-1"></p>
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-widest">Sinopsis</h4>
                            <p id="modalDescription" class="text-sm text-neutral-300 mt-1.5 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                {{-- Playlist Episode Placeholder --}}
                <div class="mt-8 border-t border-white/5 pt-6">
                    <h3 class="text-sm font-semibold text-white mb-1">Daftar Episode</h3>
                    <p class="text-xs text-neutral-400 mb-4">Klik Episode Untuk Mulai Menonton</p>
                    <div id="modalEpisodesList" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        {{-- Buttons will be injected here --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/prasunkDrama.js') }}"></script>
    @if(!empty($trendingDramas))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const swiper = new Swiper('.trending-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 16,
                grabCursor: true,
                loop: true,
                freeMode: {
                    enabled: true,
                    momentum: false,
                },
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                speed: 2000,
                navigation: {
                    nextEl: '.trending-next',
                    prevEl: '.trending-prev',
                },
            });
        });
    </script>
    @endif
    @endpush
</x-layout.app>
