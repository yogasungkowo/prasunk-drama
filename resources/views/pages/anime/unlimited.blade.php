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

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white">📚 Semua Anime</h1>
            <p class="text-sm text-neutral-400 mt-2">Koleksi lengkap anime subtitle Indonesia.</p>
        </div>

        @if(!empty($animeList))
            <div class="grid grid-cols-2 gap-4 sm:gap-5 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($animeList as $anime)
                @php $slug = $anime['slug'] ?? $anime['id'] ?? ''; @endphp
                @if($slug)
                <a href="{{ route('anime.detail', $slug) }}" class="group rounded-2xl border border-white/5 bg-white/[0.02] p-3 sm:p-4 transition-all duration-300 hover:border-red-500/20 hover:bg-white/[0.04]">
                    <div class="mb-3 sm:mb-4 aspect-[3/4] w-full rounded-xl overflow-hidden bg-neutral-900">
                        <img src="{{ $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '' }}" alt="{{ $anime['title'] }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    </div>
                    <div class="space-y-1.5 sm:space-y-2">
                        <h2 class="text-sm sm:text-base font-semibold text-white truncate">{{ $anime['title'] }}</h2>
                        <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-xs">
                            <span class="rounded-full border border-white/10 px-2 py-0.5 sm:px-2.5 sm:py-1 text-neutral-300">{{ $anime['type'] ?? $anime['status'] ?? 'Anime' }}</span>
                            @if(!empty($anime['rating']))
                            <span class="text-red-300"><i class="ri-star-fill text-[10px]"></i> {{ $anime['rating'] }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
                <h3 class="text-lg font-semibold text-white">Data anime belum tersedia</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba beberapa saat lagi.</p>
            </div>
        @endif
    </section>

</x-layout.app>
