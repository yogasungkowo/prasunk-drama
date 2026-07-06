<x-layout.app :title="'Cari: ' . $keyword . ' - Prasunk Anime'" :description="'Hasil pencarian anime ' . $keyword . ' subtitle Indonesia.'">

    <section class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Beranda Anime
            </a>
        </div>

        <div class="mb-10">
            <p class="font-display text-5xl text-red-400">Pencarian</p>
            <h1 class="mt-2 text-3xl font-extrabold text-white">Hasil untuk <span class="text-red-500">"{{ $keyword }}"</span></h1>
        </div>

        @if(!empty($results))
            <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach ($results as $anime)
                @php $slug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? ''; @endphp
                @if($slug)
                <a href="{{ route('anime.detail', $slug) }}" class="group relative flex flex-col rounded-2xl bg-neutral-900 border border-white/5 overflow-hidden hover:border-red-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                    <div class="aspect-[3/4] w-full relative overflow-hidden bg-neutral-950">
                        <img src="{{ $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '' }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 group-hover:opacity-80" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                        
                        @if(!empty($anime['episodes']))
                        <div class="absolute top-3 right-3 bg-neutral-800 text-white text-xs font-bold px-2.5 py-1 rounded-md shadow-lg backdrop-blur-md bg-opacity-90 border border-white/10">
                            Ep {{ $anime['episodes'] }}
                        </div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1 z-10 -mt-8">
                        <h3 class="text-sm sm:text-base font-semibold text-white line-clamp-2 group-hover:text-red-400 transition-colors drop-shadow-md">{{ $anime['title'] ?? '' }}</h3>
                        <div class="mt-auto pt-3 flex flex-wrap items-center justify-between gap-2 text-xs">
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2 py-1 font-medium text-neutral-300">
                                {{ $anime['status'] ?? 'Anime' }}
                            </span>
                            @if(!empty($anime['score']) || !empty($anime['rating']))
                            <span class="text-yellow-400 font-medium"><i class="ri-star-fill"></i> {{ $anime['score'] ?? $anime['rating'] }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-neutral-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <h3 class="text-lg font-semibold text-white">Tidak ada hasil untuk "{{ $keyword }}"</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba kata kunci lainnya.</p>
            </div>
        @endif
    </section>

</x-layout.app>
