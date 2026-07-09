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
        <x-anime.card :anime="$anime" />
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
