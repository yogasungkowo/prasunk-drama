<x-layout.app title="Kesalahan Server - Prasunk Drama">
    <section class="mx-auto flex min-h-[70vh] w-full max-w-7xl flex-col items-center justify-center px-6 py-24 lg:px-8 text-center">
        <div class="relative mb-8">
            <div class="absolute -inset-4 rounded-full bg-red-600/20 blur-xl"></div>
            <p class="relative font-display text-9xl font-extrabold text-red-600 drop-shadow-2xl">500</p>
        </div>
        <h1 class="mt-4 text-balance text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Kesalahan Internal Server</h1>
        <p class="mt-6 text-pretty text-lg font-medium text-neutral-400 sm:text-xl/8 max-w-2xl mx-auto">Ups, terjadi masalah pada server kami saat memproses permintaan Anda. Tim kami sedang menanganinya.</p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/" class="rounded-full bg-red-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-500 hover:scale-105 active:scale-95 transition-all">
                <i class="ri-home-4-line mr-2"></i>Kembali ke Beranda
            </a>
            <button onclick="window.location.reload()" class="rounded-full border border-white/10 bg-white/[0.02] px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/[0.05] hover:text-red-300 transition-all">
                <i class="ri-refresh-line mr-2"></i>Coba Lagi
            </button>
        </div>
    </section>
</x-layout.app>
