<x-admin-layout>
    <x-slot:header>Tambah Merek Baru</x-slot:header>

    <div class="max-w-2xl mx-auto px-4 py-6 sm:py-8">
        <div class="card overflow-hidden">
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-primary-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 tracking-tight">Registrasi Merek Baru</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Tambahkan produsen sparepart otomotif</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.brand.store') }}" class="px-6 sm:px-8 py-6 sm:py-7 space-y-6">
                @csrf

                <div>
                    <x-input-label for="nama" value="Nama Merek" />
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                           placeholder="Contoh: Toyota, Honda, Bosch..." class="form-input-clean" required autofocus>
                    <p class="text-[10px] text-slate-400 mt-1.5">Slug URL akan otomatis dihasilkan dari nama merek.</p>
                    @error('nama') <p class="form-error"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.brand.index') }}" class="btn-secondary px-6">Batal</a>
                    <button type="submit" class="btn-primary px-8 shadow-lg shadow-primary-500/20">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Merek
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
