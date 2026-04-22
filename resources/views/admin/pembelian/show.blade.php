<x-admin-layout>
    <x-slot:header>Detail Transaksi</x-slot:header>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.pembelian.index') }}" class="btn-secondary shadow-sm text-xs sm:text-sm py-1.5 px-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Transaksi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Invoice Detail --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Invoice Header --}}
            <div class="card p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 pb-5 border-b border-slate-100">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nomor Invoice</p>
                        <h2 class="text-lg sm:text-xl font-mono font-bold text-slate-800">{{ $pembelian->invoice_no }}</h2>
                    </div>
                    <div>
                        @if($pembelian->status === 'dikonfirmasi')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Dikonfirmasi
                            </span>
                        @elseif($pembelian->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Menunggu Konfirmasi
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Informasi Pembeli</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($pembelian->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $pembelian->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $pembelian->user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Waktu Transaksi</p>
                        <p class="font-bold text-slate-800">{{ $pembelian->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Pukul {{ $pembelian->created_at->format('H:i') }} WIB — {{ $pembelian->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                {{-- Product Detail --}}
                <div class="border-t border-slate-100 pt-5">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3">Detail Produk</p>
                    
                    <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        @if($pembelian->produk && $pembelian->produk->gambar)
                            <img src="{{ asset('storage/' . $pembelian->produk->gambar) }}" alt="{{ $pembelian->produk->nama }}" class="w-20 h-20 rounded-xl object-contain bg-white border border-slate-200 flex-shrink-0" loading="lazy">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200 flex-shrink-0">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 text-base sm:text-lg">
                                {{ $pembelian->produk ? $pembelian->produk->nama : 'Produk Telah Dihapus' }}
                            </p>
                            @if($pembelian->produk)
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    <span class="text-xs px-2 py-0.5 rounded bg-primary-50 text-primary-700 font-medium">{{ $pembelian->produk->brand->nama ?? '-' }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">{{ $pembelian->produk->kategori->nama ?? '-' }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $pembelian->produk->kode }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="mt-5 bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex justify-between py-2 text-sm">
                            <span class="text-slate-500">Harga Satuan</span>
                            <span class="font-medium text-slate-800">Rp {{ number_format($pembelian->produk ? $pembelian->produk->harga : ($pembelian->total / max(1, $pembelian->jumlah)), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-sm border-b border-slate-200">
                            <span class="text-slate-500">Jumlah Beli</span>
                            <span class="font-medium text-slate-800">× {{ $pembelian->jumlah }} item</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="font-bold text-slate-800 text-base">Total Pembayaran</span>
                            <span class="font-extrabold text-primary-600 text-xl">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="lg:col-span-1">
            <div class="card p-5 sm:p-6 sticky top-24">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Aksi Transaksi
                </h3>
                
                @if($pembelian->status === 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mb-5">
                        <p class="text-sm text-yellow-800 font-medium">
                            <svg class="w-4 h-4 inline mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Transaksi ini menunggu konfirmasi Anda. Periksa detail dan beri keputusan.
                        </p>
                    </div>
                    
                    <form action="{{ route('admin.pembelian.updateStatus', $pembelian) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="status" value="dikonfirmasi" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-xl shadow-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Pembayaran
                        </button>
                        <button type="submit" name="status" value="ditolak" class="w-full bg-white text-red-600 border border-red-200 font-bold py-3 px-4 rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2" onclick="return confirm('Tolak transaksi ini? Stok akan dikembalikan.')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak & Kembalikan Stok
                        </button>
                    </form>
                @else
                    <div class="text-center py-6 bg-slate-50 rounded-xl border border-slate-100">
                        @if($pembelian->status === 'dikonfirmasi')
                            <div class="w-14 h-14 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm font-bold text-green-700">Transaksi Dikonfirmasi</p>
                            <p class="text-xs text-slate-400 mt-1">Diproses pada {{ $pembelian->updated_at->format('d M Y, H:i') }}</p>
                        @else
                            <div class="w-14 h-14 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <p class="text-sm font-bold text-red-700">Transaksi Ditolak</p>
                            <p class="text-xs text-slate-400 mt-1">Stok telah dikembalikan</p>
                        @endif
                    </div>
                @endif

                {{-- Timeline --}}
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3">Riwayat</p>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-slate-300 mt-1.5 flex-shrink-0"></div>
                            <div>
                                <p class="text-xs font-medium text-slate-600">Pesanan dibuat</p>
                                <p class="text-[10px] text-slate-400">{{ $pembelian->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($pembelian->status !== 'pending')
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full {{ $pembelian->status === 'dikonfirmasi' ? 'bg-green-500' : 'bg-red-500' }} mt-1.5 flex-shrink-0"></div>
                            <div>
                                <p class="text-xs font-medium text-slate-600">{{ $pembelian->status === 'dikonfirmasi' ? 'Dikonfirmasi admin' : 'Ditolak admin' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $pembelian->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
