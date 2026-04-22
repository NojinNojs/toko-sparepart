<x-admin-layout>
    <x-slot:header>Kelola Transaksi</x-slot:header>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-slate-800">{{ $pembelian->total() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-0.5">Total Transaksi</p>
        </div>
        <div class="bg-yellow-50 rounded-xl border border-yellow-100 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-yellow-700">{{ \App\Models\Pembelian::where('status', 'pending')->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-yellow-500 font-semibold mt-0.5">Menunggu</p>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-100 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-green-700">{{ \App\Models\Pembelian::where('status', 'dikonfirmasi')->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-green-500 font-semibold mt-0.5">Dikonfirmasi</p>
        </div>
        <div class="bg-red-50 rounded-xl border border-red-100 p-3 sm:p-4 text-center">
            <p class="text-2xl font-extrabold text-red-700">{{ \App\Models\Pembelian::where('status', 'ditolak')->count() }}</p>
            <p class="text-[10px] uppercase tracking-wider text-red-500 font-semibold mt-0.5">Ditolak</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.pembelian.index') }}"
           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.pembelian.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            ⏳ Menunggu
        </a>
        <a href="{{ route('admin.pembelian.index', ['status' => 'dikonfirmasi']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm {{ request('status') === 'dikonfirmasi' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            ✅ Dikonfirmasi
        </a>
        <a href="{{ route('admin.pembelian.index', ['status' => 'ditolak']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm {{ request('status') === 'ditolak' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            ❌ Ditolak
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="card overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $item)
                        <tr class="group {{ $item->status === 'pending' ? 'bg-yellow-50/30' : '' }}">
                            <td>
                                <a href="{{ route('admin.pembelian.show', $item) }}" class="font-mono text-xs text-primary-600 font-bold hover:text-primary-700 transition-colors">
                                    {{ $item->invoice_no }}
                                </a>
                            </td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($item->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $item->user->name ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $item->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    @if($item->produk && $item->produk->gambar)
                                        <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="" class="w-10 h-10 rounded-lg object-contain bg-white border border-slate-200 flex-shrink-0" loading="lazy">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-700 truncate max-w-[160px]">{{ $item->produk->nama ?? 'Produk dihapus' }}</p>
                                        @if($item->produk && $item->produk->brand)
                                            <p class="text-[10px] text-slate-400">{{ $item->produk->brand->nama }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold">{{ $item->jumlah }}×</span>
                            </td>
                            <td class="text-right font-extrabold text-slate-900 text-sm">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td class="text-center">
                                <div>
                                    <p class="text-xs text-slate-600 font-medium">{{ $item->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.pembelian.show', $item) }}" class="btn-icon text-slate-500 bg-slate-50 hover:bg-slate-100" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if($item->status === 'pending')
                                        <form method="POST" action="{{ route('admin.pembelian.updateStatus', $item) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="dikonfirmasi">
                                            <button type="submit" class="btn-icon text-green-600 bg-green-50 hover:bg-green-100" title="Konfirmasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.pembelian.updateStatus', $item) }}" onsubmit="return confirm('Tolak transaksi ini?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="btn-icon text-red-600 bg-red-50 hover:bg-red-100" title="Tolak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-500">Belum ada transaksi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($pembelian as $item)
            <a href="{{ route('admin.pembelian.show', $item) }}" class="card p-4 block hover:shadow-card transition-shadow {{ $item->status === 'pending' ? 'border-l-4 border-l-yellow-400' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-xs text-primary-600 font-bold bg-primary-50 px-2 py-0.5 rounded">{{ $item->invoice_no }}</span>
                    <x-status-badge :status="$item->status" />
                </div>
                <div class="flex items-center gap-3 mb-2">
                    @if($item->produk && $item->produk->gambar)
                        <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="" class="w-12 h-12 rounded-lg object-contain bg-white border border-slate-200 flex-shrink-0" loading="lazy">
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $item->produk->nama ?? 'Produk dihapus' }}</p>
                        <p class="text-xs text-slate-500">{{ $item->user->name ?? '-' }} — {{ $item->jumlah }}× item</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</span>
                    <span class="font-bold text-sm text-slate-900">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                </div>
                @if($item->status === 'pending')
                    <div class="flex gap-2 mt-3" onclick="event.preventDefault(); event.stopPropagation();">
                        <form method="POST" action="{{ route('admin.pembelian.updateStatus', $item) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dikonfirmasi">
                            <button type="submit" class="w-full bg-green-50 text-green-700 font-bold text-xs py-2 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">✅ Konfirmasi</button>
                        </form>
                        <form method="POST" action="{{ route('admin.pembelian.updateStatus', $item) }}" class="flex-1" onsubmit="return confirm('Tolak?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="w-full bg-red-50 text-red-600 font-bold text-xs py-2 rounded-lg border border-red-200 hover:bg-red-100 transition-colors">❌ Tolak</button>
                        </form>
                    </div>
                @endif
            </a>
        @empty
            <div class="card p-10 text-center text-slate-500">Belum ada transaksi.</div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 pagination-clean">
        {{ $pembelian->withQueryString()->links() }}
    </div>
</x-admin-layout>
