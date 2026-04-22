@props(['produk'])

<div class="card-product group flex flex-col h-full bg-white overflow-hidden">
    {{-- Image Section — responsive aspect ratio --}}
    <div class="relative pt-[80%] sm:pt-[100%] overflow-hidden bg-slate-100 border-b border-slate-100">
        @if($produk->gambar)
            <img src="{{ asset('storage/' . $produk->gambar) }}" 
                 alt="{{ $produk->nama }}"
                 loading="lazy"
                 class="absolute top-0 left-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <div class="absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-[10px] text-slate-300 mt-1 font-medium">Tidak ada gambar</span>
            </div>
        @endif

        {{-- Stock Indicator Overlay --}}
        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 flex flex-col gap-2">
            @if($produk->stok == 0)
                <span class="bg-slate-900/80 backdrop-blur-sm px-1.5 py-0.5 sm:px-2 sm:py-1 flex items-center justify-center rounded-md font-bold text-[9px] sm:text-[10px] uppercase tracking-wider text-white">Habis</span>
            @elseif($produk->stok <= 10)
                <span class="bg-red-600/90 backdrop-blur-sm px-1.5 py-0.5 sm:px-2 sm:py-1 flex items-center justify-center rounded-md font-bold text-[9px] sm:text-[10px] uppercase tracking-wider text-white">Terbatas</span>
            @endif
        </div>
    </div>

    {{-- Content Section --}}
    <div class="p-3 sm:p-4 flex flex-col flex-grow">
        {{-- Kategori & Brand --}}
        <div class="flex items-center justify-between mt-auto mb-1.5 sm:mb-2">
            @if($produk->kategori)
                <span class="text-[10px] sm:text-xs text-slate-500 truncate pr-2">{{ $produk->kategori->nama }}</span>
            @endif
            @if($produk->brand)
                <span class="text-[10px] sm:text-xs font-semibold text-primary-600 truncate bg-primary-50 px-1.5 py-0.5 sm:px-2 rounded">{{ $produk->brand->nama }}</span>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="text-xs sm:text-sm font-medium text-slate-800 leading-snug line-clamp-2 mb-1.5 sm:mb-2 group-hover:text-primary-600 transition-colors">
            <a href="{{ route('produk.show', $produk) }}" class="focus:outline-none">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $produk->nama }}
            </a>
        </h3>

        {{-- Price --}}
        <div class="mt-auto pt-1.5 sm:pt-2 flex items-center justify-between">
            <p class="text-base sm:text-lg font-bold text-slate-900">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </p>
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white text-slate-400 transition-colors">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </div>
    </div>
</div>
