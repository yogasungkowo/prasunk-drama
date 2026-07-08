@if($animeList->count() > 0)
    <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($animeList as $anime)
        @php
            $slug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';
            $rating = $anime['score'] ?? $anime['rating'] ?? '';
            $poster = $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '';
            $status = $anime['status'] ?? '';
            $isComplete = strtolower($status) === 'complete' || strtolower($status) === 'completed' || strtolower($status) === 'tamat';
            $metaLabel = $isComplete ? 'Tamat' : ($anime['releaseDay'] ?? $status ?: ($anime['type'] ?? 'Anime'));
        @endphp
        @continue(empty($slug))
        <a href="{{ route('anime.detail', $slug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
            <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                @if($poster)
                <img src="{{ $poster }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                @else
                <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-neutral-900 via-neutral-800 to-red-950/30">
                    <i class="ri-movie-2-line text-4xl text-red-400/70"></i>
                </div>
                @endif
                @if(!empty($anime['episodes']))
                <div class="absolute top-2 right-2 rounded-full border border-white/10 bg-neutral-900/85 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                    {{ $anime['episodes'] }} Eps
                </div>
                @endif
            </div>
            <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] ?? '' }}</h3>
                <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                    @if($isComplete)
                    <span class="inline-flex items-center gap-1 rounded-full border border-green-500/20 px-2 py-0.5 sm:px-2.5 sm:py-1 text-green-400">
                        <i class="ri-check-line"></i> {{ $metaLabel }}
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                        <i class="ri-calendar-line text-neutral-400"></i> {{ $metaLabel }}
                    </span>
                    @endif
                    @if($rating)
                    <span class="inline-flex items-center gap-1 text-yellow-400 font-medium"><i class="ri-star-fill text-[10px]"></i> {{ $rating }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @if($animeList->hasPages())
    <nav class="mt-10 flex flex-wrap items-center justify-center gap-2" data-anime-pagination>
        @if($animeList->onFirstPage())
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 text-neutral-700">
            <i class="ri-arrow-left-s-line"></i>
        </span>
        @else
        <a href="{{ $animeList->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10">
            <i class="ri-arrow-left-s-line"></i>
        </a>
        @endif

        @foreach(range(max(1, $animeList->currentPage() - 2), min($animeList->lastPage(), $animeList->currentPage() + 2)) as $page)
            @if($page === $animeList->currentPage())
            <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-red-600 px-3 text-sm font-semibold text-white shadow-lg shadow-red-600/30">{{ $page }}</span>
            @else
            <a href="{{ $animeList->url($page) }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-white/10 px-3 text-sm font-medium text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10">{{ $page }}</a>
            @endif
        @endforeach

        @if($animeList->hasMorePages())
        <a href="{{ $animeList->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 text-neutral-300 transition hover:border-red-500/30 hover:text-white hover:bg-red-500/10">
            <i class="ri-arrow-right-s-line"></i>
        </a>
        @else
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 text-neutral-700">
            <i class="ri-arrow-right-s-line"></i>
        </span>
        @endif
    </nav>
    @endif
@else
    <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
        <h3 class="text-lg font-semibold text-white">Data anime belum tersedia</h3>
        <p class="text-sm text-neutral-500 mt-2">Coba ubah filter atau cek kembali beberapa saat lagi.</p>
    </div>
@endif
