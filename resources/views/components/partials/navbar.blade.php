<header class="sticky top-0 z-50 border-b border-white/5 bg-neutral-950/80 backdrop-blur-xl">
    <nav class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-6 py-4 lg:px-8">
        <a href="/" class="font-display text-3xl text-white tracking-tight transition hover:text-red-300">
            Prasunk <span class="text-red-400">Drama</span>
        </a>

        <div class="flex items-center gap-6">
            {{-- Platform links (desktop dropdown/tabs) --}}
            <div class="hidden items-center gap-2 text-sm text-neutral-400 md:flex">
                <span class="text-xs text-neutral-600 mr-2 uppercase tracking-widest font-semibold">Source:</span>
                @foreach(array_slice($platforms ?? [], 0, 4) as $key => $name)
                    <a href="?source={{ $key }}" class="px-3 py-1.5 rounded-full text-xs font-medium transition {{ ($selectedSource ?? '') == $key ? 'bg-red-950/50 text-red-400 border border-red-900/30' : 'text-neutral-400 hover:text-white' }}">
                        {{ $name }}
                    </a>
                @endforeach
            </div>

            {{-- Daftar Drama dropdown --}}
            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-medium text-neutral-200 transition hover:border-red-500/30 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-2.625 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-2.625 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5c0 .621-.504 1.125-1.125 1.125m1.5 0h12m-12 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m12-3.75c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5m1.5 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-1.5-3.75h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M6 12h12" />
                    </svg>
                    Pilih Source
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </summary>
                <div class="absolute right-0 mt-2 w-56 max-h-80 overflow-y-auto rounded-2xl border border-white/5 bg-neutral-900/95 p-2 text-sm shadow-xl backdrop-blur-xl">
                    @foreach($platforms ?? [] as $key => $name)
                        <a href="?source={{ $key }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-neutral-300 transition hover:bg-white/5 hover:text-red-300 {{ ($selectedSource ?? '') == $key ? 'text-red-400 font-semibold bg-white/[0.02]' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                            </svg>
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
            </details>
        </div>
    </nav>
</header>