<x-layout.app :title="($batch['title'] ?? 'Download Batch') . ' - Prasunk Anime'" :description="'Download batch anime subtitle Indonesia.'" :image="$batch['poster'] ?? $batch['cover'] ?? null">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5M21 12H3" />
                </svg>
                Kembali
            </a>
        </div>

        @if($batch)
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-1/3 xl:w-1/4">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-neutral-900 border border-white/5">
                    <img src="{{ $batch['poster'] ?? $batch['cover'] ?? '' }}" alt="{{ $batch['title'] }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                </div>
            </div>

            <div class="flex-1 space-y-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-white leading-tight">{{ $batch['title'] ?? 'Batch Download' }}</h1>
                    @if(!empty($batch['info']))
                    <p class="text-sm text-neutral-400 mt-2">{{ $batch['info'] }}</p>
                    @endif
                </div>

                @if(!empty($batch['download_links']) || !empty($batch['links']))
                @php $links = $batch['download_links'] ?? $batch['links'] ?? []; @endphp
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-white">Link Download</h4>
                    @foreach ($links as $link)
                    @php
                        $url = $link['url'] ?? $link['link'] ?? '';
                        $label = $link['label'] ?? $link['quality'] ?? $link['server'] ?? 'Download';
                    @endphp
                    @if($url)
                    <a href="{{ $url }}" target="_blank" class="flex items-center justify-between p-4 rounded-2xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] transition group">
                        <span class="text-sm text-neutral-300 group-hover:text-white">{{ $label }}</span>
                        <i class="ri-download-2-line text-red-400"></i>
                    </a>
                    @endif
                    @endforeach
                </div>
                @endif

                @if(!empty($batch['synopsis']))
                <div class="border-t border-white/5 pt-4">
                    <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-widest">Sinopsis</h4>
                    <p class="text-sm text-neutral-300 mt-2 leading-relaxed">{{ $batch['synopsis'] }}</p>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
            <h3 class="text-lg font-semibold text-white">Batch tidak ditemukan</h3>
            <p class="text-sm text-neutral-500 mt-2">Halaman batch tidak tersedia.</p>
        </div>
        @endif
    </div>

</x-layout.app>
