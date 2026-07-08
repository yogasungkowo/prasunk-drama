// Suggestion Search Engine
const searchInput = document.getElementById('searchInput');
const suggestionBox = document.getElementById('suggestionBox');
let searchTimeout = null;

if (searchInput && suggestionBox) {
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);

        if (query.length < 2) {
            suggestionBox.classList.add('hidden');
            return;
        }

        // Get the active source from the URL or fallback to dramabox
        const urlParams = new URLSearchParams(window.location.search);
        const selectedSource = urlParams.get('source') || 'dramabox';

        searchTimeout = setTimeout(() => {
            fetch(`/drama/suggest?source=${selectedSource}&query=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        suggestionBox.innerHTML = '<div class="p-4 text-xs text-neutral-500 text-center">Tidak ada hasil ditemukan</div>';
                        suggestionBox.classList.remove('hidden');
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `
                            <a href="/drama/play?id=${item.id}&source=${item.source}" class="flex items-center gap-3 p-2 rounded-xl transition hover:bg-white/[0.04] group">
                                <div class="w-12 h-16 rounded-lg overflow-hidden bg-neutral-950 shrink-0">
                                    <img src="${item.cover}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1594909122845-11baa439b7bf?q=80&w=300&auto=format&fit=crop'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h4 class="text-sm font-semibold text-white truncate group-hover:text-red-400 transition">${item.title}</h4>
                                        <span class="text-xs text-red-300 shrink-0">⭐ ${item.rating}</span>
                                    </div>
                                    <p class="text-xs text-neutral-500 line-clamp-2 mt-1 leading-normal">${item.description}</p>
                                </div>
                            </a>
                        `;
                    });
                    suggestionBox.innerHTML = html;
                    suggestionBox.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Suggest error:', err);
                });
        }, 300);
    });

    // Hide overlay suggestion box when clicking outside
    document.addEventListener('click', function(e) {
        const container = document.getElementById('searchContainer');
        if (container && !container.contains(e.target)) {
            suggestionBox.classList.add('hidden');
        }
    });
}

function openDramaModal(id, source) {
    const modal = document.getElementById('dramaModal');
    const loading = document.getElementById('modalLoading');
    const content = document.getElementById('modalContent');
    
    if (!modal || !loading || !content) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    loading.classList.remove('hidden');
    content.classList.add('hidden');

    fetch(`/drama/detail?id=${id}&source=${source}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
        .then(res => res.json())
        .then(response => {
            if (response.code === 200 && response.data) {
                const drama = response.data;
                document.getElementById('modalTitle').innerText = drama.title;
                document.getElementById('modalCover').src = drama.cover;
                document.getElementById('modalDescription').innerText = drama.description || 'Tidak ada sinopsis.';
                document.getElementById('modalPlatform').innerText = source.toUpperCase();
                
                const eps = drama.episodes || [];
                document.getElementById('modalEpisodes').innerText = `${eps.length} Episode Tersedia`;

                const list = document.getElementById('modalEpisodesList');
                list.innerHTML = '';
                eps.forEach(ep => {
                    const btn = document.createElement('button');
                    btn.className = `py-2 text-xs font-semibold rounded-lg text-center transition ${ep.locked ? 'bg-white/5 text-neutral-500 cursor-not-allowed' : 'bg-red-950/20 text-red-400 border border-red-900/10 hover:bg-red-900/30'}`;
                    btn.innerHTML = ep.locked ? `${ep.number} 🔒` : ep.number;
                    if(!ep.locked) {
                        btn.onclick = () => window.location.href = `/drama/play?id=${id}&source=${source}&ep=${ep.number}`;
                    }
                    list.appendChild(btn);
                });

                loading.classList.add('hidden');
                content.classList.remove('hidden');
            } else {
                alert('Gagal mengambil data drama.');
                closeDramaModal();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan sistem.');
            closeDramaModal();
        });
}

function closeDramaModal() {
    const modal = document.getElementById('dramaModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function sharePage(title, text, url) {
    title = decodeURIComponent(title);
    text = decodeURIComponent(text);
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 z-50 rounded-xl bg-neutral-900 border border-white/10 px-4 py-3 text-xs text-white shadow-2xl flex items-center gap-2 transition duration-300';
            toast.innerHTML = `<i class="ri-checkbox-circle-fill text-green-500 text-sm"></i> Link berhasil disalin ke clipboard!`;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    }
}
