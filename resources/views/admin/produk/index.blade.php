<x-admin-layout>
    <x-slot:header>Kelola Produk</x-slot:header>

    {{-- Summary Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-slate-800">{{ $produk->total() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-0.5">Total Produk</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-emerald-600">{{ $produk->where('stok', '>', 20)->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-0.5">Stok Aman</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-amber-600">{{ $produk->whereBetween('stok', [1, 20])->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-0.5">Stok Terbatas</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-red-600">{{ $produk->where('stok', 0)->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-0.5">Stok Habis</p>
        </div>
    </div>

    {{-- Top Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        {{-- Search --}}
        <form method="GET" action="{{ route('admin.produk.index') }}" class="flex-1 max-w-sm">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="form-input-clean pl-10 py-2 text-sm w-full">
            </div>
        </form>
        <a href="{{ route('admin.produk.create') }}" class="btn-primary shadow-md flex-shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="card overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th class="w-16">Gambar</th>
                        <th>Info Produk</th>
                        <th>Kategori & Merek</th>
                        <th class="text-right">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $item)
                        <tr class="group">
                            <td>
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="w-14 h-14 rounded-lg object-cover border border-slate-200" loading="lazy">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <a href="{{ route('admin.produk.show', $item) }}" class="font-bold text-slate-800 hover:text-primary-600 transition-colors">{{ $item->nama }}</a>
                                    <p class="font-mono text-xs text-slate-400 mt-0.5">{{ $item->kode }}</p>
                                    @if($item->deskripsi)
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($item->deskripsi, 60) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">{{ $item->kategori->nama ?? '-' }}</span>
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-primary-50 text-primary-700">{{ $item->brand->nama ?? '-' }}</span>
                                    @if($item->tipe_kendaraan)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            {{ \App\Models\Produk::TIPE_KENDARAAN[$item->tipe_kendaraan] }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-right font-extrabold text-slate-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->stok > 20)
                                    <span class="badge badge-success">{{ $item->stok }}</span>
                                @elseif($item->stok > 0)
                                    <span class="badge badge-warning">{{ $item->stok }}</span>
                                @else
                                    <span class="badge badge-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.produk.show', $item) }}" class="btn-icon text-slate-500 bg-slate-50 hover:bg-slate-100" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.produk.edit', $item) }}" class="btn-icon text-blue-600 bg-blue-50 hover:bg-blue-100" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.produk.destroy', $item) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-600 bg-red-50 hover:bg-red-100" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-500">Belum ada produk di etalase.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($produk as $item)
            <div class="card p-4">
                <div class="flex items-start gap-3">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-slate-200 flex-shrink-0" loading="lazy">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200 flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('admin.produk.show', $item) }}" class="font-bold text-sm text-slate-800 hover:text-primary-600 block truncate">{{ $item->nama }}</a>
                        <p class="font-mono text-[10px] text-slate-400 mt-0.5">{{ $item->kode }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">{{ $item->kategori->nama ?? '-' }}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-primary-50 text-primary-600 font-medium">{{ $item->brand->nama ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                    <p class="font-bold text-slate-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <div class="flex items-center gap-2">
                        @if($item->stok > 20)
                            <span class="badge badge-success text-xs">{{ $item->stok }}</span>
                        @elseif($item->stok > 0)
                            <span class="badge badge-warning text-xs">{{ $item->stok }}</span>
                        @else
                            <span class="badge badge-danger text-xs">Habis</span>
                        @endif
                        <a href="{{ route('admin.produk.edit', $item) }}" class="btn-icon text-blue-600 bg-blue-50 hover:bg-blue-100 w-8 h-8">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center text-slate-500">Belum ada produk di etalase.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 pagination-clean">
        {{ $produk->withQueryString()->links() }}
    </div>
</x-admin-layout>
