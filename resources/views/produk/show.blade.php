<x-app-layout>
    <x-slot:title>{{ $produk->nama }} — Toko Sparepart</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in-up">
        
        {{-- Breadcrumb --}}
        <nav class="flex items-center text-sm text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Beranda</a>
            <svg class="w-4 h-4 mx-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-800 font-medium truncate">{{ $produk->nama }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            {{-- ===== 1. KIRI: GAMBAR PRODUK ===== --}}
            <div class="w-full lg:w-[400px] flex-shrink-0">
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm aspect-square relative sticky top-24">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                             alt="{{ $produk->nama }}"
                             loading="lazy"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                            <svg class="w-24 h-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ===== 2. TENGAH: INFO PRODUK ===== --}}
            <div class="flex-1 w-full min-w-0">
                <div class="pb-6 border-b border-slate-200">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight mb-2">
                        {{ $produk->nama }}
                    </h1>
                    
                    <div class="flex items-center gap-4 text-sm mt-3">
                        @if($produk->stok > 0)
                            <div class="flex items-center text-slate-600">
                                <span class="text-slate-500 mr-2">Stok:</span>
                                <span class="font-bold text-slate-800">{{ $produk->stok }} unit</span>
                            </div>
                        @else
                            <span class="badge badge-danger">Stok Habis</span>
                        @endif

                        <div class="h-4 w-px bg-slate-300"></div>

                        @if($produk->kode)
                            <div class="flex items-center text-slate-600">
                                <span class="text-slate-500 mr-2">Kode:</span>
                                <span class="font-mono text-sm">{{ $produk->kode }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="py-6 border-b border-slate-200">
                    <p class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="py-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Detail Produk</h3>
                    
                    <div class="grid grid-cols-3 gap-y-4 text-sm mb-6 max-w-sm">
                        <div class="text-slate-500">Merek</div>
                        <div class="col-span-2 font-medium text-primary-600">{{ $produk->brand->nama ?? '-' }}</div>
                        
                        <div class="text-slate-500">Kategori</div>
                        <div class="col-span-2 font-medium text-slate-800">{{ $produk->kategori->nama ?? '-' }}</div>
                    </div>

                    <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
                        @if($produk->deskripsi)
                            {!! nl2br(e($produk->deskripsi)) !!}
                        @else
                            Tidak ada deskripsi produk.
                        @endif
                    </div>
                </div>
            </div>

            {{-- ===== 3. KANAN: CARD CHECKOUT (STICKY) ===== --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white border border-slate-200 rounded-xl shadow-lg p-5 sticky top-24">
                    <h2 class="text-lg font-bold text-slate-800 mb-4">Atur Pembelian</h2>

                    @auth
                        @if(auth()->user()->isCustomer())
                            @if($produk->stok > 0)
                                <div x-data="{
                                    jumlah: 1,
                                    harga: {{ $produk->harga }},
                                    stok: {{ $produk->stok }},
                                    get total() { return this.jumlah * this.harga; },
                                    formatRupiah(val) { return new Intl.NumberFormat('id-ID').format(val); }
                                }">
                                    <form method="POST" action="{{ route('checkout.store') }}">
                                        @csrf
                                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                                        {{-- Input Jumlah (Plus Minus style) --}}
                                        <div class="flex items-center gap-4 mb-5">
                                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden h-10 w-32">
                                                <button type="button" @click="if(jumlah > 1) jumlah--" class="w-10 h-full bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center font-bold text-lg select-none transition-colors">−</button>
                                                <input type="number" name="jumlah" x-model.number="jumlah" min="1" :max="stok" class="w-12 h-full text-center border-none focus:ring-0 text-sm font-semibold p-0" readonly>
                                                <button type="button" @click="if(jumlah < stok) jumlah++" class="w-10 h-full bg-slate-50 text-slate-600 hover:bg-slate-100 flex items-center justify-center font-bold text-lg select-none transition-colors">+</button>
                                            </div>
                                            <p class="text-xs text-slate-500">Maks. beli <span x-text="stok" class="font-bold"></span></p>
                                        </div>

                                        {{-- Subtotal --}}
                                        <div class="flex justify-between items-center mb-6">
                                            <span class="text-slate-500">Subtotal</span>
                                            <span class="text-lg font-bold text-slate-900">Rp <span x-text="formatRupiah(total)"></span></span>
                                        </div>

                                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold text-base py-3 rounded-lg shadow-sm focus:ring-4 focus:ring-primary-500/30 transition-all active:scale-[0.98]">
                                            Beli Langsung
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-slate-50 p-4 rounded-lg text-center mb-4 border border-slate-200">
                                    <p class="text-slate-500 font-medium">Barang ini sedang tidak tersedia.</p>
                                </div>
                                <button disabled class="w-full bg-slate-200 text-slate-400 font-bold py-3 rounded-lg cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        @else
                            {{-- Admin View --}}
                            <div class="bg-blue-50 text-blue-800 p-4 rounded-lg text-sm mb-4 border border-blue-100">
                                Profil Admin tidak dapat melakukan pembelian.
                            </div>
                            <a href="{{ route('admin.produk.edit', $produk) }}" class="flex items-center justify-center w-full bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm py-3 rounded-lg shadow-sm transition-all">
                                Edit Produk Ini
                            </a>
                        @endif
                    @else
                        {{-- Guest View --}}
                        <div class="bg-primary-50 text-primary-800 p-4 rounded-lg text-sm mb-5 border border-primary-100">
                            Masuk ke akun Anda untuk melakukan pembelian suku cadang.
                        </div>
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full bg-white border border-primary-600 text-primary-600 hover:bg-primary-50 font-bold text-sm py-3 rounded-lg shadow-sm transition-all hover:shadow">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
