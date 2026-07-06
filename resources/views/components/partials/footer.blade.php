<footer class="border-t border-white/5 bg-neutral-950">
    <div class="mx-auto grid w-full max-w-7xl gap-10 px-6 py-14 lg:grid-cols-4 lg:px-8">
        <div class="lg:col-span-2">
            <h3 class="text-xl font-bold text-white">Prasunk <span class="font-display text-2xl text-red-400">Drama</span></h3>
            <p class="mt-3 max-w-md text-sm leading-relaxed text-neutral-400">
                Website streaming drama China & anime gratis subtitle Indonesia. Nikmati tontonan favorit dari berbagai platform dalam satu tempat dengan tampilan modern dan update rutin.
            </p>
        </div>

        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wide text-red-400">Navigasi</h4>
            <ul class="mt-4 space-y-2 text-sm text-neutral-400">
                <li><a href="/" class="transition hover:text-white">Drama</a></li>
                <li><a href="{{ route('anime.index') }}" class="transition hover:text-white">Anime</a></li>
                <li><a href="{{ route('anime.ongoing') }}" class="transition hover:text-white">Anime Ongoing</a></li>
                <li><a href="{{ route('anime.schedule') }}" class="transition hover:text-white">Jadwal Rilis</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wide text-red-400">Konten</h4>
            <ul class="mt-4 space-y-2 text-sm text-neutral-400">
                <li><a href="{{ route('anime.complete') }}" class="transition hover:text-white">Anime Tamat</a></li>
                <li><a href="{{ route('anime.genre') }}" class="transition hover:text-white">Genre Anime</a></li>
                <li><a href="{{ route('anime.unlimited') }}" class="transition hover:text-white">Semua Anime</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/5 py-4 text-center text-xs text-neutral-500">
        &copy; {{ date('Y') }} Prasunk Drama. Semua hak cipta dilindungi.
    </div>
</footer>