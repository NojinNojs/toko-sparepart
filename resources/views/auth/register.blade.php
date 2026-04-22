<x-guest-layout>
    <x-slot:title>Daftar — Toko Sparepart Otomotif</x-slot:title>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Buat Akun</h2>
        <p class="text-slate-500 font-medium">Bergabunglah untuk mendapatkan akses penuh ke produk sparepart terbaik.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="name" class="block mt-1 w-full pl-12 form-input-clean" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Anda" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <x-text-input id="email" class="block mt-1 w-full pl-12 form-input-clean" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <x-text-input id="password" class="block mt-1 w-full pl-12 form-input-clean"
                                type="password"
                                name="password"
                                required autocomplete="new-password"
                                placeholder="Min. 8 karakter" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <x-text-input id="password_confirmation" class="block mt-1 w-full pl-12 form-input-clean"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Ulangi kata sandi" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" 
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-wait"
                    x-bind:disabled="isSubmitting">
                <template x-if="isSubmitting">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-show="!isSubmitting">Daftar Sekarang</span>
                <span x-show="!isSubmitting">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span x-show="isSubmitting" class="text-white/80">Memproses...</span>
            </button>
        </div>

        <p class="text-center text-sm font-medium text-slate-500 mt-8">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-bold underline decoration-primary-200 underline-offset-4 decoration-2 hover:decoration-primary-600 transition-all">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
