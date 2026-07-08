@php
    $synopsisText = 'Detail anime subtitle Indonesia.';
    $poster = $anime['poster'] ?? $anime['cover'] ?? $anime['thumbnail'] ?? '';
    if(isset($anime['synopsis']) && is_array($anime['synopsis'])) {
        $synopsisText = implode(' ', $anime['synopsis']['paragraphs'] ?? []);
    } elseif(is_string($anime['synopsis'] ?? null)) {
        $synopsisText = strip_tags($anime['synopsis']);
    }
@endphp
<x-layout.app :title="($anime['title'] ?? 'Detail Anime') . ' - Prasunk Anime'" :description="\Illuminate\Support\Str::limit($synopsisText, 150)" :image="$anime['poster'] ?? $anime['cover'] ?? null">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Beranda Anime
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            {{-- Left Sidebar: Poster & Meta --}}
            <div class="w-full lg:w-1/3 xl:w-1/4 shrink-0">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-neutral-900 border border-white/10 shadow-2xl relative group">
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-transparent to-transparent z-10 opacity-60"></div>
                    @if($poster)
                    <img src="{{ $poster }}" alt="{{ $anime['title'] ?? '' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    @else
                    <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-neutral-900 via-neutral-800 to-red-950/30">
                        <i class="ri-movie-2-line text-6xl text-red-400/70"></i>
                    </div>
                    @endif
                </div>

                <div class="mt-6 p-5 rounded-2xl bg-neutral-900/50 border border-white/5 backdrop-blur-sm space-y-4">
                    @if(!empty($anime['score']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Rating</span>
                        <span class="flex items-center gap-1.5 text-sm font-bold text-yellow-400">
                            <i class="ri-star-fill"></i> {{ $anime['score'] }}
                        </span>
                    </div>
                    <hr class="border-white/5">
                    @endif
                    
                    @if(!empty($anime['status']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Status</span>
                        <span class="text-xs font-semibold {{ strtolower($anime['status']) == 'ongoing' ? 'text-green-400' : 'text-blue-400' }}">
                            {{ $anime['status'] }}
                        </span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($anime['type']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Tipe</span>
                        <span class="text-xs font-medium text-white">{{ $anime['type'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($anime['episodes']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Total Episode</span>
                        <span class="text-xs font-medium text-white">{{ $anime['episodes'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif
                    
                    @if(!empty($anime['duration']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Durasi</span>
                        <span class="text-xs font-medium text-white">{{ $anime['duration'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($anime['studios']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Studio</span>
                        <span class="text-xs font-medium text-white text-right">{{ $anime['studios'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Content --}}
            <div class="flex-1 min-w-0">
                <div class="mb-8">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-neutral-400 leading-tight mb-2">{{ $anime['title'] ?? '' }}</h1>
                    @if(!empty($anime['japanese']))
                    <p class="text-base sm:text-lg text-neutral-500 font-medium">{{ $anime['japanese'] }}</p>
                    @endif
                </div>

                @if(!empty($anime['genreList']) || !empty($anime['genre']))
                @php $genres = $anime['genreList'] ?? $anime['genre'] ?? []; @endphp
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach ($genres as $genre)
                    @php 
                        $genreTitle = is_string($genre) ? $genre : ($genre['title'] ?? $genre['name'] ?? $genre['genreName'] ?? '');
                        $genreId = is_string($genre) ? \Illuminate\Support\Str::slug($genre) : ($genre['genreId'] ?? $genre['slug'] ?? \Illuminate\Support\Str::slug($genreTitle));
                    @endphp
                    @if($genreTitle && $genreId)
                    <a href="{{ route('anime.genre.list', $genreId) }}" class="rounded-lg bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-medium text-red-400 hover:bg-red-500/20 hover:border-red-500/30 transition-all">
                        {{ $genreTitle }}
                    </a>
                    @elseif($genreTitle)
                    <span class="rounded-lg bg-red-500/10 border border-red-500/20 px-3 py-1.5 text-xs font-medium text-red-400">
                        {{ $genreTitle }}
                    </span>
                    @endif
                    @endforeach
                </div>
                @endif

                @if(!empty($anime['synopsis']['paragraphs']) || (is_string($anime['synopsis'] ?? null) && !empty($anime['synopsis'])))
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-red-500"></i> Sinopsis
                    </h3>
                    <div class="space-y-4 text-sm sm:text-base text-neutral-300 leading-relaxed bg-neutral-900/30 p-6 rounded-2xl border border-white/5">
                        @if(isset($anime['synopsis']['paragraphs']) && is_array($anime['synopsis']['paragraphs']))
                            @foreach($anime['synopsis']['paragraphs'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        @else
                            <p>{!! nl2br(e(strip_tags($anime['synopsis']))) !!}</p>
                        @endif
                    </div>
                </div>
                @endif

                @php $batchSlug = is_array($anime['batch'] ?? null) ? ($anime['batch']['batchId'] ?? $anime['batch']['slug'] ?? '') : ($anime['batch_slug'] ?? $anime['batch'] ?? ''); @endphp
                @if(!empty($batchSlug))
                <div class="mb-10">
                    <a href="{{ route('anime.batch', $batchSlug) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-red-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-0.5 transition-all w-full sm:w-auto">
                        <i class="ri-download-cloud-2-line text-lg"></i>
                        Download Batch Full Episode
                    </a>
                </div>
                @endif

                @php $episodes = $anime['episodeList'] ?? $anime['episode_list'] ?? $anime['episodes'] ?? []; @endphp
                @if(is_array($episodes) && count($episodes) > 0 && !isset($episodes[0]) && !is_array($episodes))
                    {{-- handle if it's just a number string --}}
                @elseif(is_array($episodes) && count($episodes) > 0)
                <div id="episodes">
                    <div class="flex items-end justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="ri-play-list-2-line text-red-500"></i> Daftar Episode
                            </h3>
                            <p class="text-sm text-neutral-400 mt-1">Pilih episode untuk mulai menonton</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                        @foreach ($episodes as $ep)
                        @if(is_array($ep))
                            @php
                                $epSlug = $ep['episodeId'] ?? $ep['slug'] ?? $ep['id'] ?? '';
                                $epNum = $ep['eps'] ?? $ep['episode'] ?? $ep['number'] ?? $loop->iteration;
                                $epDate = $ep['date'] ?? '';
                            @endphp
                            @if($epSlug)
                            <a href="{{ route('anime.episode', $epSlug) }}" class="group relative flex flex-col items-center justify-center p-4 rounded-xl bg-neutral-900 border border-white/5 hover:border-red-500/30 hover:bg-neutral-800 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/10">
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-br from-neutral-300 to-neutral-600 group-hover:from-red-400 group-hover:to-red-600 transition-colors">
                                    {{ is_numeric($epNum) ? sprintf("%02d", $epNum) : $epNum }}
                                </span>
                                @if($epDate)
                                <span class="text-[10px] text-neutral-500 mt-2 text-center truncate w-full group-hover:text-neutral-400">{{ $epDate }}</span>
                                @endif
                            </a>
                            @endif
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

        @include('components.anime.related-grid', [
            'relatedAnime' => $relatedAnime ?? null,
            'sectionClass' => 'mt-12',
        ])
    </div>

</x-layout.app>
