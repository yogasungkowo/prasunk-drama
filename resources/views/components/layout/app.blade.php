<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=carattere:400&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @stack('styles')
</head>
<body class="antialiased text-neutral-100 bg-neutral-950">
    <div class="relative min-h-screen overflow-x-hidden">
        <div class="pointer-events-none fixed inset-0 -z-10 bg-linear-to-b from-red-950/20 via-neutral-950 to-neutral-950"></div>
        <div class="pointer-events-none fixed -top-60 left-1/2 -translate-x-1/2 -z-10 h-[500px] w-[800px] rounded-full bg-red-600/8 blur-3xl"></div>

        <x-partials.navbar :platforms="$platforms" :selectedSource="$selectedSource" />
        <main>
            {{ $slot }}
        </main>
        <x-partials.footer />
    </div>

    @stack('scripts')
    
</body>
</html>