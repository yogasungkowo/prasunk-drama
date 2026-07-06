@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'url' => null,
    'platforms' => [],
    'selectedSource' => 'dramabox'
])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Prasunk Drama - Streaming Short Drama  Terpopuler' }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description ?? 'Nonton short drama  terpopuler secara gratis dengan subtitle Indonesia. Temukan drama trending dari Dramabox, Reelshort, Shortmax, dan lainnya.' }}">
    <meta name="keywords" content="short drama, drama , sub indo, dramabox, reelshort, shortmax, streaming drama, nonton gratis">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $url ?? request()->url() }}">
    <meta property="og:title" content="{{ $title ?? 'Prasunk Drama - Streaming Short Drama  Terpopuler' }}">
    <meta property="og:description" content="{{ $description ?? 'Nonton short drama  terpopuler secara gratis dengan subtitle Indonesia. Temukan drama trending dari Dramabox, Reelshort, Shortmax, dan lainnya.' }}">
    <meta property="og:image" content="{{ $image ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=1200&auto=format&fit=crop' }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $url ?? request()->url() }}">
    <meta property="twitter:title" content="{{ $title ?? 'Prasunk Drama - Streaming Short Drama  Terpopuler' }}">
    <meta property="twitter:description" content="{{ $description ?? 'Nonton short drama  terpopuler secara gratis dengan subtitle Indonesia. Temukan drama trending dari Dramabox, Reelshort, Shortmax, dan lainnya.' }}">
    <meta property="twitter:image" content="{{ $image ?? 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=1200&auto=format&fit=crop' }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=carattere:400&display=swap" rel="stylesheet" />

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @stack('styles')
</head>
<body class="antialiased text-neutral-100 bg-neutral-950">
    <div class="relative min-h-screen overflow-x-hidden">
        <div class="pointer-events-none fixed inset-0 -z-10 bg-linear-to-b from-red-950/20 via-neutral-950 to-neutral-950"></div>
        <div class="pointer-events-none fixed -top-60 left-1/2 -translate-x-1/2 -z-10 h-[500px] w-[800px] rounded-full bg-red-600/8 blur-3xl"></div>

                <x-partials.navbar :platforms="$platforms ?? []" :selectedSource="$selectedSource ?? 'dramabox'" />
        <main>
            {{ $slot }}
        </main>
        <x-partials.footer />
    </div>

    @stack('scripts')
    
</body>
</html>