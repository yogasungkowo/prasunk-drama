<x-layout.app :title="'Cari: ' . $keyword . ' - Prasunk Anime'" :description="'Hasil pencarian anime ' . $keyword . ' subtitle Indonesia.'">

    <section class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Kembali ke Beranda Anime
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
                <x-anime.card :anime="$anime" />
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
