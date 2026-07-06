<x-layout.app title="Jadwal Rilis Anime - Prasunk Anime" description="Jadwal rilis anime subtitle Indonesia lengkap setiap hari.">

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
            <p class="font-display text-5xl text-red-400">Update Harian</p>
            <h1 class="mt-2 text-3xl font-extrabold text-white flex items-center gap-3">
                <i class="ri-calendar-event-fill text-red-500"></i> Jadwal Rilis <span class="text-red-500">Anime</span>
            </h1>
            <p class="text-sm text-neutral-400 mt-2">Jadwal rilis anime subtitle Indonesia lengkap setiap hari.</p>
        </div>

        @if(!empty($schedule))
            <div class="space-y-8">
                @foreach ($schedule as $dayData)
                    @php 
                        $dayName = $dayData['day'] ?? 'Unknown'; 
                        $animeList = $dayData['anime_list'] ?? [];
                    @endphp
                    @if(!empty($animeList))
                    <div class="border border-white/5 bg-white/[0.02] rounded-3xl p-6 md:p-8 backdrop-blur-sm">
                        <h2 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4 inline-block">{{ $dayName }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($animeList as $anime)
                            @php $slug = $anime['animeId'] ?? $anime['slug'] ?? $anime['id'] ?? ''; @endphp
                            @if($slug)
                            <a href="{{ route('anime.detail', $slug) }}" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-white/5 transition-all group border border-transparent hover:border-red-500/20">
                                <div class="w-16 h-20 rounded-xl overflow-hidden bg-neutral-900 shrink-0 shadow-lg">
                                    <img src="{{ $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '' }}" alt="" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-white line-clamp-2 group-hover:text-red-400 transition">{{ $anime['title'] ?? '' }}</h3>
                                    <p class="text-xs text-neutral-500 mt-1">
                                        @if(!empty($anime['episodes']))
                                        {{ $anime['episodes'] }} Eps
                                        @endif
                                        @if(!empty($anime['score']))
                                        <span class="text-yellow-400 ml-2"><i class="ri-star-fill"></i> {{ $anime['score'] }}</span>
                                        @endif
                                    </p>
                                </div>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
                <h3 class="text-lg font-semibold text-white">Jadwal belum tersedia</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba beberapa saat lagi.</p>
            </div>
        @endif
    </section>

</x-layout.app>
