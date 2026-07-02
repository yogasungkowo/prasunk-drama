<x-layout.app :title="($drama['title'] ?? 'Nonton Drama') . ' - Prasunk Drama'" :platforms="$platforms" :selectedSource="$source">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        {{-- Breadcrumbs & Back --}}
        <div class="mb-6">
            <a href="/?source={{ $source }}" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Video Player Col --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Video Container --}}
                <div class="aspect-video w-full rounded-3xl overflow-hidden border border-white/5 bg-neutral-900 shadow-2xl relative">
                    @if($videoData && !empty($videoData['videoUrl']))
                        <video class="w-full h-full object-contain" controls autoplay src="{{ $videoData['videoUrl'] }}"></video>
                    @elseif($videoData && ($videoData['locked'] ?? false))
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-neutral-950/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-white">Episode Terkunci</h3>
                            <p class="text-sm text-neutral-500 mt-2 max-w-sm">Episode ini memerlukan akun premium atau unlock langsung di aplikasi {{ $platforms[$source] ?? $source }}.</p>
                        </div>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center">
                            <div class="h-8 w-8 animate-spin rounded-full border-4 border-red-600/30 border-t-red-600"></div>
                            <p class="text-sm text-neutral-500 mt-4">Gagal memuat player video atau video tidak tersedia.</p>
                        </div>
                    @endif
                </div>

                {{-- Drama Information --}}
                <div class="border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="inline-block rounded-full bg-red-950/40 text-red-400 px-3 py-1 text-xs font-semibold border border-red-900/20 mb-2">
                                {{ strtoupper($source) }}
                            </span>
                            <h1 class="text-2xl font-bold text-white leading-tight">
                                {{ $drama['title'] }} — <span class="text-red-400">Episode {{ $currentEpisode }}</span>
                            </h1>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/5 pt-4">
                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-widest">Sinopsis</h4>
                        <p class="text-sm text-neutral-300 mt-2 leading-relaxed">
                            {{ $drama['description'] ?? 'Tidak ada sinopsis untuk drama ini.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Playlist Col --}}
            <div class="border border-white/5 bg-white/[0.01] rounded-3xl p-6 md:p-8 space-y-4 h-fit max-h-[80vh] overflow-y-auto no-scrollbar">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Episode</h3>
                    <p class="text-xs text-neutral-500 mt-1">Pilih episode untuk langsung memutar video</p>
                </div>

                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-4 gap-2 pt-2 border-t border-white/5">
                    @foreach($drama['episodes'] ?? [] as $ep)
                        @php
                            $isCurrent = $ep['number'] == $currentEpisode;
                        @endphp
                        <a href="?id={{ $drama['id'] }}&source={{ $source }}&ep={{ $ep['number'] }}" 
                           class="py-3 text-xs font-bold rounded-xl text-center transition {{ $isCurrent ? 'bg-red-600 text-white shadow-lg shadow-red-600/20' : ($ep['locked'] ? 'bg-white/5 text-neutral-600 cursor-not-allowed' : 'bg-white/[0.02] text-neutral-300 border border-white/5 hover:border-red-500/20 hover:text-red-400') }}">
                            {{ $ep['number'] }}{{ $ep['locked'] ? ' 🔒' : '' }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</x-layout.app>
