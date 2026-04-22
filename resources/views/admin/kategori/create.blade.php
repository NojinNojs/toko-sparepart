<x-admin-layout>
    <x-slot:header>Tambah Kategori Baru</x-slot:header>

    <div class="max-w-2xl mx-auto px-4 py-6 sm:py-8">
        <div class="card overflow-hidden">
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-primary-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight">Klasifikasi Produk Baru</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Tambahkan kategori untuk mengelompokkan produk</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.kategori.store') }}" class="px-6 sm:px-8 py-6 sm:py-7 space-y-6">
                @csrf

                <div>
                    <x-input-label for="nama" value="Nama Kategori" />
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                           placeholder="Contoh: Mesin, Kelistrikan, Kaki-kaki..." class="form-input-clean" required autofocus>
                    <p class="text-[10px] text-slate-400 mt-1.5">Slug URL akan otomatis dihasilkan dari nama kategori.</p>
                    @error('nama') <p class="form-error"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.kategori.index') }}" class="btn-secondary px-6">Batal</a>
                    <button type="submit" class="btn-primary px-8 shadow-lg shadow-primary-500/20">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
