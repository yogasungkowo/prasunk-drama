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
                        <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                            @foreach ($animeList as $anime)
                            <x-anime.card :anime="$anime" />
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
