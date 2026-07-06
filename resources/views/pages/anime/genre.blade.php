<x-layout.app title="Genre Anime - Prasunk Anime" description="Daftar genre anime subtitle Indonesia lengkap.">

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
            <p class="font-display text-5xl text-red-400">Pilih Kategori</p>
            <h1 class="mt-2 text-3xl font-extrabold text-white flex items-center gap-3">
                <i class="ri-price-tag-3-fill text-red-500"></i> Genre <span class="text-red-500">Anime</span>
            </h1>
            <p class="text-sm text-neutral-400 mt-2">Pilih genre untuk menemukan anime favoritmu.</p>
        </div>

        @if(!empty($genres))
            <div class="flex flex-wrap gap-3">
                @foreach ($genres as $genre)
                @php
                    $genreSlug = is_string($genre) ? Str::slug($genre) : ($genre['genreId'] ?? $genre['slug'] ?? Str::slug($genre['title'] ?? ''));
                    $genreName = is_string($genre) ? $genre : ($genre['title'] ?? $genre['name'] ?? '');
                @endphp
                @continue(empty($genreSlug))
                <a href="{{ route('anime.genre.list', $genreSlug) }}" class="rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-neutral-300 transition-all hover:border-red-500/40 hover:text-red-300 hover:bg-red-500/10 hover:shadow-lg hover:shadow-red-500/10">
                    {{ $genreName }}
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
                <h3 class="text-lg font-semibold text-white">Genre belum tersedia</h3>
                <p class="text-sm text-neutral-500 mt-2">Coba beberapa saat lagi.</p>
            </div>
        @endif
    </section>

</x-layout.app>
