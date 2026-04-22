<x-guest-layout>
    <x-slot:title>Masuk — Toko Sparepart Otomotif</x-slot:title>

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Selamat Datang</h2>
        <p class="text-slate-500 font-medium">Masuk ke akun Anda untuk mulai berbelanja sparepart berkualitas.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <x-text-input id="email" class="block mt-1 w-full pl-12 form-input-clean" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-end">
                <x-input-label for="password" :value="__('Kata Sandi')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-primary-600 hover:text-primary-700 transition-colors mb-1.5" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            
            <div class="relative group">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <x-text-input id="password" class="block mt-1 w-full pl-12 form-input-clean"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-lg border-slate-200 text-primary-600 shadow-sm focus:ring-primary-500/20 transition-all cursor-pointer" name="remember">
                <span class="ms-2 text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" 
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-wait"
                    x-bind:disabled="isSubmitting">
                <template x-if="isSubmitting">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-show="!isSubmitting">Masuk Sekarang</span>
                <span x-show="!isSubmitting">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
                <span x-show="isSubmitting" class="text-white/80">Memproses...</span>
            </button>
        </div>

        <p class="text-center text-sm font-medium text-slate-500 mt-8">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-bold underline decoration-primary-200 underline-offset-4 decoration-2 hover:decoration-primary-600 transition-all">
                Daftar Gratis
            </a>
        </p>
    </form>
</x-guest-layout>
