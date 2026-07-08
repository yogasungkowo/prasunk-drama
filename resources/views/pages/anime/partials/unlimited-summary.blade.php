@if($animeList->total() > 0)
Menampilkan <span class="font-semibold text-white">{{ $animeList->firstItem() }}</span>-<span class="font-semibold text-white">{{ $animeList->lastItem() }}</span>
dari <span class="font-semibold text-white">{{ $animeList->total() }}</span> anime
@endif
