<x-admin-layout>
    <x-slot:header>Kelola Kategori</x-slot:header>

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <div>
            <p class="text-sm text-slate-500 font-medium">Mengelola <span class="font-bold text-slate-900">{{ $kategoris->total() }}</span> kategori produk</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn-primary shadow-md flex-shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="card overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug URL</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center">Tanggal Ditambahkan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr class="group">
                            <td class="text-slate-400 text-center font-mono text-sm">{{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <span class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors">{{ $kategori->nama }}</span>
                                </div>
                            </td>
                            <td class="font-mono text-xs text-slate-400">/{{ $kategori->slug }}</td>
                            <td class="text-center">
                                @if($kategori->produk_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                        {{ $kategori->produk_count }} Produk
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada produk</span>
                                @endif
                            </td>
                            <td class="text-center text-xs text-slate-400">{{ $kategori->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn-icon text-blue-600 bg-blue-50 hover:bg-blue-100" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($kategori->produk_count == 0)
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" onsubmit="return confirm('Yakin ingin menghapus kategori {{ $kategori->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-600 bg-red-50 hover:bg-red-100" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="btn-icon text-slate-300 bg-slate-50 cursor-not-allowed" title="Tidak bisa dihapus (memiliki produk)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-500">Belum ada kategori terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($kategoris as $index => $kategori)
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $kategori->nama }}</p>
                        <p class="text-xs text-slate-400 font-mono">/{{ $kategori->slug }}</p>
                    </div>
                    <span class="text-xs font-bold {{ $kategori->produk_count > 0 ? 'text-blue-600 bg-blue-50' : 'text-slate-400 bg-slate-50' }} px-2 py-1 rounded-full flex-shrink-0">
                        {{ $kategori->produk_count }} produk
                    </span>
                </div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">{{ $kategori->created_at->format('d M Y') }}</span>
                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Edit &rarr;</a>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center text-slate-500">Belum ada kategori terdaftar.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($kategoris->hasPages())
        <div class="mt-6 pagination-clean">
            {{ $kategoris->links() }}
        </div>
    @endif
</x-admin-layout>
