<x-app-layout>
    <x-slot:title>Toko Sparepart Otomotif — Terlengkap & Terpercaya</x-slot:title>

    {{-- ==================== SMALL HERO BANNER ==================== --}}
    <div class="bg-primary-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12 flex flex-col md:flex-row items-center justify-between">
            <div class="text-white text-center md:text-left mb-4 md:mb-0">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight mb-2">
                    Suku Cadang Original & Berkualitas
                </h1>
                <p class="text-primary-100 max-w-xl text-sm sm:text-base">
                    Temukan semua kebutuhan otomotif Anda di sini. Harga bersaing, transaksi mudah, dan kualitas terjamin.
                </p>
            </div>
            <div class="flex gap-4">
                <a href="#katalog" class="bg-white text-primary-600 hover:bg-primary-50 px-6 py-3 rounded-lg font-bold text-sm shadow-sm transition-colors flex items-center">
                    Belanja Sekarang
                </a>
            </div>
        </div>
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- ===== KIRI: FILTER SIDEBAR ===== --}}
            <div x-data="{ showFilter: false }" class="w-full lg:w-64 flex-shrink-0 lg:sticky lg:top-24">
                
                {{-- Mobile Filter Toggle Button --}}
                <button @click="showFilter = !showFilter" class="lg:hidden w-full bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between shadow-sm mb-4">
                    <span class="font-bold text-slate-800">Filter & Kategori</span>
                    <svg class="w-5 h-5 text-slate-500 transition-transform" :class="showFilter ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <aside 
                    x-show="showFilter" 
                    x-bind:class="showFilter ? 'block' : 'hidden'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="lg:!block">
                    
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-slate-800 tracking-tight">Filter</h3>
                            @if(request()->hasAny(['search', 'kategori', 'brand']))
                                <a href="{{ route('home') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Hapus Semua</a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('home') }}" class="space-y-5">
                            
                            {{-- Search --}}
                            <div>
                                <label for="search" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Cari Produk</label>
                                <div class="relative">
                                    <input type="text"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Nama, kode..."
                                           class="form-input-clean pl-9">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Kategori</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    <label class="flex items-center">
                                        <input type="radio" name="kategori" value="" class="text-primary-600 border-slate-300 focus:ring-primary-500" {{ request('kategori') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="ml-2 text-sm text-slate-600">Semua Kategori</span>
                                    </label>
                                    @foreach($kategoris as $kat)
                                        <label class="flex items-center">
                                            <input type="radio" name="kategori" value="{{ $kat->id }}" class="text-primary-600 border-slate-300 focus:ring-primary-500" {{ request('kategori') == $kat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span class="ml-2 text-sm text-slate-600">{{ $kat->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            {{-- Brand --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Merek / Brand</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    <label class="flex items-center">
                                        <input type="radio" name="brand" value="" class="text-primary-600 border-slate-300 focus:ring-primary-500" {{ request('brand') == '' ? 'checked' : '' }} onchange="this.form.submit()">
                                        <span class="ml-2 text-sm text-slate-600">Semua Merek</span>
                                    </label>
                                    @foreach($brands as $br)
                                        <label class="flex items-center">
                                            <input type="radio" name="brand" value="{{ $br->id }}" class="text-primary-600 border-slate-300 focus:ring-primary-500" {{ request('brand') == $br->id ? 'checked' : '' }} onchange="this.form.submit()">
                                            <span class="ml-2 text-sm text-slate-600">{{ $br->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Filter button for mobile (desktop auto-submits mostly, but good to have) --}}
                            <button type="submit" class="w-full bg-slate-100 text-slate-700 hover:bg-slate-200 py-2 rounded-md text-sm font-semibold transition-colors mt-2">
                                Terapkan Filter
                            </button>
                        </form>
                    </div>
                </aside>
            </div>

            {{-- ===== KANAN: PRODUCT GRID ===== --}}
            <div class="flex-1 w-full">
                
                {{-- Info Bar --}}
                <div class="flex items-center justify-between mb-4 px-1">
                    <p class="text-sm text-slate-600">Menampilkan <span class="font-semibold text-slate-900">{{ $produk->total() }}</span> produk</p>
                </div>

                @if($produk->count() > 0)
                    <div class="grid grid-cols-1 min-[400px]:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 product-grid">
                        @foreach($produk as $index => $item)
                            <div class="stagger-{{ ($index % 8) + 1 }} h-full">
                                <x-product-card :produk="$item" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10 pagination-clean border-t border-slate-200 pt-8">
                        {{ $produk->withQueryString()->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-16 text-center animate-fade-in-up">
                        <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 10h.01M15 10h.01M9 14h6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Produk Tidak Ditemukan</h3>
                        <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Kami tidak dapat menemukan produk yang sesuai dengan kriteria pencarian atau filter Anda.</p>
                        <a href="{{ route('home') }}" class="btn-secondary">
                            Reset Pencarian
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </section>
</x-app-layout>
