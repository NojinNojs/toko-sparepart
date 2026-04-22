<x-app-layout>
    <x-slot:title>Riwayat Pembelian — Toko Sparepart</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 animate-fade-in-up">
        
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Belanja</h1>
            <p class="text-slate-500 mt-1">Lacak status pesanan dan pembelian Anda sebelumnya.</p>
        </div>

        @if($pembelian->count() > 0)
            <div class="space-y-4">
                @foreach($pembelian as $item)
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-card transition-shadow duration-200 overflow-hidden">
                        
                        {{-- Card Header: Status & Invoice --}}
                        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <x-status-badge :status="$item->status" />
                                <span class="text-xs text-slate-400 font-medium whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                            <span class="text-xs font-mono text-slate-500">{{ $item->invoice_no }}</span>
                        </div>

                        {{-- Card Body: Product Info --}}
                        <div class="p-5 flex flex-col sm:flex-row gap-5">
                            {{-- Image --}}
                            <div class="w-20 h-20 flex-shrink-0 border border-slate-200 rounded-lg overflow-hidden bg-slate-100">
                                @if($item->produk && $item->produk->gambar)
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama }}" class="w-full h-full object-contain bg-white">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h4 class="text-base font-bold text-slate-900 truncate mb-1">
                                    {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                </h4>
                                <p class="text-sm text-slate-500 mb-2">
                                    {{ $item->jumlah }} barang × Rp {{ number_format($item->produk->harga ?? 0, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Total Price block --}}
                            <div class="sm:border-l sm:border-slate-200 sm:pl-5 flex flex-col justify-center sm:items-end mt-4 sm:mt-0">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wider font-semibold">Total Belanja</p>
                                <p class="text-lg font-extrabold text-slate-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8 pagination-clean">
                {{ $pembelian->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white border border-slate-200 rounded-xl p-16 text-center animate-fade-in-up shadow-sm">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Belum Ada Transaksi</h3>
                <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">Anda belum melakukan pembelian apapun. Belanja suku cadang sekarang.</p>
                <a href="{{ route('home') }}" class="btn-primary">
                    Eksplor Katalog Suku Cadang
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
