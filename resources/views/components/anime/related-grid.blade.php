@php
    $related = $relatedAnime ?? null;
    $sectionClass = $sectionClass ?? 'mt-12';
@endphp

@if(!empty($related['animeList']))
<section class="{{ $sectionClass }}">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="ri-price-tag-3-line text-red-500"></i>
                Anime Genre {{ $related['genreTitle'] ?? 'Serupa' }}
            </h2>
            <p class="text-sm text-neutral-400 mt-1">Rekomendasi anime dari genre yang sama.</p>
        </div>

        @if(!empty($related['genreSlug']))
        <a href="{{ route('anime.genre.list', $related['genreSlug']) }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-red-400 hover:text-red-300 transition">
            Lihat Semua <i class="ri-arrow-right-line"></i>
        </a>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach($related['animeList'] as $anime)
        @php
            $animeSlug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';
            $poster = $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '';
            $meta = $anime['releaseDay'] ?? $anime['status'] ?? $anime['type'] ?? 'Anime';
            $rating = $anime['score'] ?? $anime['rating'] ?? '';
            $subMeta = $anime['latestReleaseDate'] ?? '';
        @endphp
        @if($animeSlug)
        <a href="{{ route('anime.detail', $animeSlug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
            <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                <img src="{{ $poster }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                @if(!empty($anime['episodes']))
                <div class="absolute top-2 right-2 rounded-full border border-red-500/20 bg-red-600/90 px-2 py-0.5 text-[10px] font-semibold text-white shadow-lg shadow-black/20">
                    Ep {{ $anime['episodes'] }}
                </div>
                @endif
            </div>
            <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] ?? '' }}</h3>
                <div class="mt-auto flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                        {{ $meta }}
                    </span>
                    @if($rating)
                    <span class="inline-flex items-center gap-1 text-yellow-400 font-medium">
                        <i class="ri-star-fill text-[10px]"></i> {{ $rating }}
                    </span>
                    @elseif($subMeta)
                    <span class="text-neutral-400">{{ $subMeta }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endif
        @endforeach
    </div>
</section>
@endif
