@php
    $title = $batch['title'] ?? $batch['animeTitle'] ?? 'Download Batch';
    $poster = $batch['poster'] ?? $batch['cover'] ?? null;
    $genres = $batch['genreList'] ?? $batch['genres'] ?? $batch['genre'] ?? [];
    $downloadFormats = $batch['downloadUrl']['formats']
        ?? $batch['download_url']['formats']
        ?? $batch['downloads']['formats']
        ?? [];
    $downloadQualities = $batch['downloadUrl']['qualities']
        ?? $batch['download_url']['qualities']
        ?? $batch['download_links']
        ?? $batch['links']
        ?? [];

    if (empty($downloadFormats) && !empty($downloadQualities)) {
        $downloadFormats = [[
            'title' => $title . ' Batch Subtitle Indonesia',
            'qualities' => $downloadQualities,
        ]];
    }

    $metaItems = [
        'Japanese' => $batch['japanese'] ?? null,
        'Tipe' => $batch['type'] ?? null,
        'Episode' => $batch['episodes'] ?? null,
        'Durasi' => $batch['duration'] ?? null,
        'Studio' => $batch['studios'] ?? null,
        'Rilis' => $batch['aired'] ?? null,
        'Credit' => $batch['credit'] ?? null,
    ];

    $synopsis = $batch['synopsis'] ?? null;
    if (is_array($synopsis)) {
        $synopsis = implode("\n\n", $synopsis['paragraphs'] ?? $synopsis);
    }
@endphp

<x-layout.app :title="$title . ' - Prasunk Anime'" :description="'Download batch anime subtitle Indonesia.'" :image="$poster">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali
            </a>
        </div>

        @if($batch)
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <aside class="w-full lg:w-1/3 xl:w-1/4 shrink-0">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-neutral-900 border border-white/10 shadow-2xl relative group">
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-transparent to-transparent z-10 opacity-60"></div>
                    <img src="{{ $poster ?? '' }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                </div>

                <div class="mt-6 rounded-2xl bg-neutral-900/50 border border-white/5 backdrop-blur-sm p-5 space-y-4">
                    @if(!empty($batch['score']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Rating</span>
                        <span class="flex items-center gap-1.5 text-sm font-bold text-yellow-400">
                            <i class="ri-star-fill"></i> {{ $batch['score'] }}
                        </span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @foreach($metaItems as $label => $value)
                        @if(!empty($value))
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs text-neutral-400">{{ $label }}</span>
                            <span class="text-xs font-medium text-white text-right">{{ $value }}</span>
                        </div>
                        @if(!$loop->last)
                        <hr class="border-white/5">
                        @endif
                        @endif
                    @endforeach
                </div>
            </aside>

            <main class="flex-1 min-w-0">
                <div class="mb-8">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-red-500/20 bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-300">
                        <i class="ri-download-cloud-2-line"></i>
                        Batch Download
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-neutral-400 leading-tight">{{ $title }}</h1>
                    @if(!empty($batch['info']))
                    <p class="text-sm text-neutral-400 mt-3">{{ $batch['info'] }}</p>
                    @endif
                </div>

                @if(!empty($genres))
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach ($genres as $genre)
                    @php
                        $genreId = is_string($genre) ? \Illuminate\Support\Str::slug($genre) : ($genre['genreId'] ?? $genre['slug'] ?? \Illuminate\Support\Str::slug($genre['title'] ?? $genre['name'] ?? ''));
                        $genreTitle = is_string($genre) ? $genre : ($genre['title'] ?? $genre['name'] ?? '');
                    @endphp
                    @if($genreTitle)
                    <a href="{{ $genreId ? route('anime.genre.list', $genreId) : '#' }}" class="rounded-lg bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-medium text-red-400 hover:bg-red-500/20 hover:border-red-500/30 transition-all">
                        {{ $genreTitle }}
                    </a>
                    @endif
                    @endforeach
                </div>
                @endif

                <section class="mb-10">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="ri-download-2-line text-red-500"></i>
                            Link Download Batch
                        </h2>
                        <p class="text-sm text-neutral-400 mt-1">Pilih resolusi dan server download yang tersedia.</p>
                    </div>

                    @if(!empty($downloadFormats))
                    <div class="space-y-6">
                        @foreach($downloadFormats as $format)
                        @php
                            $qualities = $format['qualities'] ?? [];
                            if (empty($qualities) && !empty($format['urls'])) {
                                $qualities = [$format];
                            }
                        @endphp

                        <div class="rounded-3xl border border-white/5 bg-white/[0.02] p-5 md:p-6 backdrop-blur-sm">
                            @if(!empty($format['title']))
                            <h3 class="text-base font-bold text-white mb-5">{{ $format['title'] }}</h3>
                            @endif

                            @if(!empty($qualities))
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($qualities as $quality)
                                @php
                                    $urls = $quality['urls'] ?? [];
                                    if (empty($urls) && (!empty($quality['url']) || !empty($quality['link']))) {
                                        $urls = [[
                                            'title' => $quality['server'] ?? $quality['label'] ?? $quality['title'] ?? 'Download',
                                            'url' => $quality['url'] ?? $quality['link'],
                                        ]];
                                    }
                                @endphp

                                <div class="rounded-2xl border border-white/5 bg-neutral-900/60 p-4 hover:border-white/10 transition-colors">
                                    <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3 mb-4">
                                        <h4 class="font-bold text-white">{{ $quality['title'] ?? $quality['quality'] ?? 'Download' }}</h4>
                                        @if(!empty($quality['size']))
                                        <span class="shrink-0 rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400">{{ $quality['size'] }}</span>
                                        @endif
                                    </div>

                                    @if(!empty($urls))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($urls as $link)
                                        @php $url = $link['url'] ?? $link['link'] ?? ''; @endphp
                                        @if($url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-lg bg-white/5 border border-white/10 px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-white hover:bg-white/20 transition-all">
                                            <i class="ri-external-link-line text-red-400"></i>
                                            {{ $link['title'] ?? $link['server'] ?? $link['label'] ?? 'Link' }}
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-sm text-neutral-500">Link untuk kualitas ini belum tersedia.</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-neutral-500">Belum ada kualitas download untuk format ini.</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="rounded-2xl border border-white/5 bg-white/[0.02] p-6 text-center">
                        <h3 class="text-base font-semibold text-white">Link download belum tersedia</h3>
                        <p class="text-sm text-neutral-500 mt-2">API belum mengirim daftar link untuk batch ini.</p>
                    </div>
                    @endif
                </section>

                @if(!empty($synopsis))
                <section class="rounded-3xl border border-white/5 bg-neutral-900/30 p-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-red-500"></i>
                        Sinopsis
                    </h2>
                    <div class="space-y-4 text-sm sm:text-base text-neutral-300 leading-relaxed">
                        @foreach(preg_split('/\n{2,}/', trim(strip_tags($synopsis))) as $paragraph)
                        <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </section>
                @endif
            </main>
        </div>

        @include('components.anime.related-grid', [
            'relatedAnime' => $relatedAnime ?? null,
            'sectionClass' => 'mt-12',
        ])
        @else
        <div class="text-center py-20 border border-white/5 rounded-2xl bg-white/[0.01]">
            <h3 class="text-lg font-semibold text-white">Batch tidak ditemukan</h3>
            <p class="text-sm text-neutral-500 mt-2">Halaman batch tidak tersedia.</p>
        </div>
        @endif
    </div>

</x-layout.app>
