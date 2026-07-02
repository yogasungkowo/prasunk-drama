<x-layout.app title="Sedang Perbaikan - Prasunk Drama">
    <section class="mx-auto flex min-h-[70vh] w-full max-w-7xl flex-col items-center justify-center px-6 py-24 lg:px-8 text-center">
        <div class="relative mb-8">
            <div class="absolute -inset-4 rounded-full bg-red-500/20 blur-xl"></div>
            <p class="relative font-display text-9xl font-extrabold text-red-500 drop-shadow-2xl">503</p>
        </div>
        <h1 class="mt-4 text-balance text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Layanan Tidak Tersedia</h1>
        <p class="mt-6 text-pretty text-lg font-medium text-neutral-400 sm:text-xl/8 max-w-2xl mx-auto">Kami sedang melakukan perbaikan atau pemeliharaan sistem. Silakan kembali lagi dalam beberapa saat.</p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onclick="window.location.reload()" class="rounded-full bg-red-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-500 hover:scale-105 active:scale-95 transition-all">
                <i class="ri-refresh-line mr-2"></i>Coba Muat Ulang
            </button>
        </div>
    </section>
</x-layout.app>
