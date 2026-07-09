@props(['recommendedAnime' => [], 'sectionClass' => 'mt-12'])

@php
    $recommended = $recommendedAnime;
@endphp

@if(!empty($recommended))
<section class="{{ $sectionClass }}">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="ri-thumb-up-line text-red-500"></i>
            Rekomendasi Anime Lainnya
        </h2>
        <p class="text-sm text-neutral-400 mt-1">Anime yang mungkin kamu suka.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach($recommended as $anime)
        @php
            $animeSlug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? '';
            $poster = $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '';
        @endphp
        @if($animeSlug)
        <a href="{{ route('anime.detail', $animeSlug) }}" class="group flex flex-col rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
            <div class="mb-3 sm:mb-4 aspect-[3/4] w-full relative rounded-xl overflow-hidden bg-neutral-900">
                @if($poster)
                <img src="{{ $poster }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                @else
                <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-neutral-900 via-neutral-800 to-red-950/30">
                    <i class="ri-movie-2-line text-4xl text-red-400/70"></i>
                </div>
                @endif
            </div>
            <div class="flex flex-1 flex-col space-y-1.5 sm:space-y-2">
                <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2">{{ $anime['title'] ?? '' }}</h3>
                <div class="mt-auto flex flex-wrap items-center gap-1 text-[10px] sm:text-xs">
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">
                        <i class="ri-sparkle-line text-yellow-400"></i> Rekomendasi
                    </span>
                </div>
            </div>
        </a>
        @endif
        @endforeach
    </div>
</section>
@endif
