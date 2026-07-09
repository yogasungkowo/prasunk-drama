@php
    $synopsisText = 'Detail movie anime subtitle Indonesia.';
    $poster = $movie['poster'] ?? $movie['cover'] ?? $movie['thumbnail'] ?? '';
    if(isset($movie['synopsis']) && is_array($movie['synopsis'])) {
        $synopsisText = implode(' ', $movie['synopsis']['paragraphs'] ?? []);
    } elseif(is_string($movie['synopsis'] ?? null)) {
        $synopsisText = strip_tags($movie['synopsis']);
    }
@endphp
<x-layout.app :title="($movie['title'] ?? 'Detail Movie Anime') . ' - Prasunk Anime'" :description="\Illuminate\Support\Str::limit($synopsisText, 150)" :image="$movie['poster'] ?? $movie['cover'] ?? null">

    <div class="mx-auto w-full max-w-7xl px-6 pt-12 pb-24 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('anime.movies') }}" class="inline-flex items-center gap-2 text-sm font-medium text-neutral-400 hover:text-red-400 transition-colors group">
                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar Movie
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            {{-- Left Sidebar: Poster & Meta --}}
            <div class="w-full lg:w-1/3 xl:w-1/4 shrink-0">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-neutral-900 border border-white/10 shadow-2xl relative group">
                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900 via-transparent to-transparent z-10 opacity-60"></div>
                    @if($poster)
                    <img src="{{ $poster }}" alt="{{ $movie['title'] ?? '' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                    @else
                    <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-neutral-900 via-neutral-800 to-red-950/30">
                        <i class="ri-movie-2-line text-6xl text-red-400/70"></i>
                    </div>
                    @endif
                </div>

                <div class="mt-6 p-5 rounded-2xl bg-neutral-900/50 border border-white/5 backdrop-blur-sm space-y-4">
                    @if(!empty($movie['score']['value']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Rating</span>
                        <div class="text-right">
                            <span class="flex items-center justify-end gap-1.5 text-sm font-bold text-yellow-400">
                                <i class="ri-star-fill"></i> {{ $movie['score']['value'] }}
                            </span>
                            @if(!empty($movie['score']['users']))
                            <span class="text-[10px] text-neutral-500">{{ $movie['score']['users'] }}</span>
                            @endif
                        </div>
                    </div>
                    <hr class="border-white/5">
                    @endif
                    
                    @if(!empty($movie['status']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Status</span>
                        <span class="text-xs font-semibold {{ strtolower($movie['status']) == 'ongoing' ? 'text-green-400' : 'text-blue-400' }}">
                            {{ $movie['status'] }}
                        </span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($movie['type']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Tipe</span>
                        <span class="text-xs font-medium text-white">{{ $movie['type'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($movie['duration']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Durasi</span>
                        <span class="text-xs font-medium text-white">{{ $movie['duration'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($movie['season']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Musim</span>
                        <span class="text-xs font-medium text-white">{{ $movie['season'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif
                    
                    @if(!empty($movie['aired']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Dirilis</span>
                        <span class="text-xs font-medium text-white text-right">{{ $movie['aired'] }}</span>
                    </div>
                    <hr class="border-white/5">
                    @endif

                    @if(!empty($movie['studios']))
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-neutral-400">Studio</span>
                        <span class="text-xs font-medium text-white text-right">{{ $movie['studios'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Content --}}
            <div class="flex-1 min-w-0">
                <div class="mb-8">
                    @php
                        $displayTitle = !empty($movie['title']) ? $movie['title'] : (!empty($movie['english']) ? $movie['english'] : ($movie['japanese'] ?? ''));
                        
                        $subtitle = '';
                        if ($displayTitle === ($movie['title'] ?? '')) {
                            $subtitle = $movie['japanese'] ?? $movie['english'] ?? '';
                        } elseif ($displayTitle === ($movie['english'] ?? '')) {
                            $subtitle = $movie['japanese'] ?? '';
                        } elseif ($displayTitle === ($movie['japanese'] ?? '')) {
                            $subtitle = $movie['english'] ?? '';
                        }
                    @endphp
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-neutral-400 leading-tight mb-2">{{ $displayTitle }}</h1>
                    @if($subtitle)
                    <p class="text-base sm:text-lg text-neutral-500 font-medium">{{ $subtitle }}</p>
                    @endif
                </div>

                @if(!empty($movie['genreList']) || !empty($movie['genre']))
                @php $genres = $movie['genreList'] ?? $movie['genre'] ?? []; @endphp
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

                @if(!empty($movie['synopsis']['paragraphs']) || (is_string($movie['synopsis'] ?? null) && !empty($movie['synopsis'])))
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ri-file-text-line text-red-500"></i> Sinopsis
                    </h3>
                    <div class="space-y-4 text-sm sm:text-base text-neutral-300 leading-relaxed bg-neutral-900/30 p-6 rounded-2xl border border-white/5">
                        @if(isset($movie['synopsis']['paragraphs']) && is_array($movie['synopsis']['paragraphs']))
                            @foreach($movie['synopsis']['paragraphs'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        @else
                            <p>{!! nl2br(e(strip_tags($movie['synopsis']))) !!}</p>
                        @endif
                    </div>
                </div>
                @endif

                @if(!empty($movie['synopsis']['connections']))
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ri-links-line text-red-500"></i> Terkait
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($movie['synopsis']['connections'] as $conn)
                            @if(!empty($conn['animeId']))
                            <a href="{{ route('anime.movies.detail', $conn['animeId']) }}" class="inline-flex items-center gap-2 rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm font-medium text-neutral-300 hover:text-white hover:bg-white/10 transition-all">
                                <i class="ri-movie-line text-red-400"></i> {{ $conn['title'] }}
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @php $episodes = $movie['episodeList'] ?? $movie['episode_list'] ?? $movie['episodes'] ?? []; @endphp
                @if(is_array($episodes) && count($episodes) > 0 && !isset($episodes[0]) && !is_array($episodes))
                    {{-- handle if it's just a number string --}}
                @elseif(is_array($episodes) && count($episodes) > 0)
                <div id="episodes">
                    <div class="flex items-end justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="ri-play-circle-line text-red-500"></i> Tonton Movie
                            </h3>
                            <p class="text-sm text-neutral-400 mt-1">Pilih server untuk mulai menonton</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                        @foreach ($episodes as $ep)
                        @if(is_array($ep))
                            @php
                                $epSlug = $ep['episodeId'] ?? $ep['slug'] ?? $ep['id'] ?? '';
                                $epTitle = $ep['title'] ?? 'Play Movie';
                            @endphp
                            @if($epSlug)
                            <a href="{{ route('anime.movies.episode', $epSlug) }}" class="group relative flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-red-900/20 to-neutral-900 border border-red-500/20 hover:border-red-500/50 hover:from-red-900/40 transition-all hover:-translate-y-1 hover:shadow-xl hover:shadow-red-900/20">
                                <span class="font-bold text-white group-hover:text-red-300 transition-colors">
                                    {{ is_numeric($epTitle) ? 'Movie Part ' . $epTitle : $epTitle }}
                                </span>
                                <i class="ri-play-fill text-2xl text-red-500 group-hover:text-red-400 transition-colors"></i>
                            </a>
                            @endif
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</x-layout.app>
