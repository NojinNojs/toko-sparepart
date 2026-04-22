<x-admin-layout>
    <x-slot:header>Kelola Merek</x-slot:header>

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <div>
            <p class="text-sm text-slate-500 font-medium">Mengelola <span class="font-bold text-slate-900">{{ $brands->total() }}</span> merek otomotif terdaftar</p>
        </div>
        <a href="{{ route('admin.brand.create') }}" class="btn-primary shadow-md flex-shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Merek
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="card overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Nama Merek</th>
                        <th>Slug URL</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center">Tanggal Ditambahkan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $index => $brand)
                        <tr class="group">
                            <td class="text-slate-400 text-center font-mono text-sm">{{ ($brands->currentPage() - 1) * $brands->perPage() + $index + 1 }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($brand->nama, 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-slate-800 group-hover:text-primary-600 transition-colors">{{ $brand->nama }}</span>
                                </div>
                            </td>
                            <td class="font-mono text-xs text-slate-400">/{{ $brand->slug }}</td>
                            <td class="text-center">
                                @if($brand->produk_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700">
                                        {{ $brand->produk_count }} Produk
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada produk</span>
                                @endif
                            </td>
                            <td class="text-center text-xs text-slate-400">{{ $brand->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.brand.edit', $brand) }}" class="btn-icon text-blue-600 bg-blue-50 hover:bg-blue-100" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($brand->produk_count == 0)
                                    <form method="POST" action="{{ route('admin.brand.destroy', $brand) }}" onsubmit="return confirm('Yakin ingin menghapus merek {{ $brand->nama }}?')">
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
                            <td colspan="6" class="text-center py-10 text-slate-500">Belum ada merek terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($brands as $index => $brand)
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($brand->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $brand->nama }}</p>
                        <p class="text-xs text-slate-400 font-mono">/{{ $brand->slug }}</p>
                    </div>
                    <span class="text-xs font-bold {{ $brand->produk_count > 0 ? 'text-primary-600 bg-primary-50' : 'text-slate-400 bg-slate-50' }} px-2 py-1 rounded-full flex-shrink-0">
                        {{ $brand->produk_count }} produk
                    </span>
                </div>
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Ditambahkan {{ $brand->created_at->format('d M Y') }}</span>
                    <a href="{{ route('admin.brand.edit', $brand) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Edit &rarr;</a>
                </div>
            </div>
        @empty
            <div class="card p-10 text-center text-slate-500">Belum ada merek terdaftar.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($brands->hasPages())
        <div class="mt-6 pagination-clean">
            {{ $brands->links() }}
        </div>
    @endif
</x-admin-layout>
