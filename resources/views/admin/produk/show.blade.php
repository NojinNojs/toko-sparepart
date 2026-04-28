<x-admin-layout>
    <x-slot:header>Detail Produk: {{ $produk->nama }}</x-slot:header>

    {{-- Breadcrumb --}}
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.produk.index') }}" class="btn-secondary shadow-sm text-xs sm:text-sm py-1.5 px-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <a href="{{ route('admin.produk.edit', $produk) }}" class="btn-primary shadow-sm text-xs sm:text-sm py-1.5 px-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Produk
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Image + Quick Info --}}
        <div class="lg:col-span-1 space-y-4">
            {{-- Product Image --}}
            <div class="card overflow-hidden">
                @if($produk->gambar)
                    <div class="aspect-square bg-slate-100">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="w-full h-full object-contain bg-white" loading="lazy">
                    </div>
                @else
                    <div class="aspect-square bg-slate-50 flex flex-col items-center justify-center text-slate-300">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="text-xs uppercase tracking-wider font-semibold">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            {{-- Status Stok --}}
            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Status Stok</span>
                    @if($produk->stok > 20)
                        <span class="badge badge-success">Tersedia</span>
                    @elseif($produk->stok > 0)
                        <span class="badge badge-warning">Terbatas</span>
                    @else
                        <span class="badge badge-danger">Habis</span>
                    @endif
                </div>
                <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $produk->stok }} <span class="text-base font-medium text-slate-400">unit</span></p>
            </div>

            {{-- Harga --}}
            <div class="card p-4 bg-primary-50 border-primary-100">
                <span class="text-xs text-primary-600/70 font-semibold uppercase tracking-wider">Harga Jual</span>
                <p class="text-2xl font-extrabold text-primary-700 mt-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Main Info --}}
            <div class="card p-5 sm:p-6">
                <div class="flex items-start justify-between mb-4 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">{{ $produk->nama }}</h2>
                        <p class="font-mono text-sm text-slate-400 mt-1">{{ $produk->kode }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Merek / Brand</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="font-bold text-primary-700">{{ $produk->brand->nama ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Kategori</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span class="font-bold text-slate-700">{{ $produk->kategori->nama ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Tipe Kendaraan</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span class="font-bold text-slate-700">
                                {{ \App\Models\Produk::TIPE_KENDARAAN[$produk->tipe_kendaraan] ?? 'Tidak ditentukan' }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Tanggal Ditambahkan</p>
                        <p class="font-medium text-slate-700">{{ $produk->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Terakhir Diperbarui</p>
                        <p class="font-medium text-slate-700">{{ $produk->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="card p-5 sm:p-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Deskripsi Produk
                </h3>
                @if($produk->deskripsi)
                    <div class="text-slate-600 leading-relaxed whitespace-pre-wrap text-sm">{{ $produk->deskripsi }}</div>
                @else
                    <p class="text-slate-400 italic text-sm">Tidak ada deskripsi untuk produk ini.</p>
                @endif
            </div>

            {{-- Danger Zone --}}
            <div class="card p-5 sm:p-6 border-red-100 bg-red-50/30">
                <h3 class="text-sm font-bold text-red-700 uppercase tracking-wider mb-3">Zona Berbahaya</h3>
                <p class="text-sm text-red-600/80 mb-4">Menghapus produk bersifat permanen dan tidak bisa dibatalkan. Pastikan produk tidak memiliki transaksi aktif.</p>
                <form method="POST" action="{{ route('admin.produk.destroy', $produk) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold text-sm py-2 px-4 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Produk Ini
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
