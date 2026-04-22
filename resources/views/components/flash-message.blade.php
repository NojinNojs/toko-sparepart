@php
    $type = null;
    $message = null;

    if (session('success') || session('status')) {
        $type = 'success';
        $message = session('success') ?? (session('status') === 'profile-updated' ? 'Profil berhasil diperbarui!' : session('status'));
    } elseif (session('error')) {
        $type = 'error';
        $message = session('error');
    } elseif (session('warning')) {
        $type = 'warning';
        $message = session('warning');
    } elseif ($errors->any() && !request()->is('login') && !request()->is('register')) {
        // Only show validation toast on non-auth pages or general flows
        // For login/register, the red text below inputs is often enough, 
        // but let's add a generic toast as requested.
        $type = 'error';
        $message = 'Terdapat kesalahan input. Silakan cek kembali data Anda.';
    } elseif ($errors->any() && (request()->is('login') || request()->is('register'))) {
        $type = 'error';
        $message = 'Gagal memproses. Harap periksa kembali form Anda.';
    }
@endphp

@if($type && $message)
<div x-data="{ 
        show: false,
        init() {
            $nextTick(() => { this.show = true });
            setTimeout(() => { this.show = false }, 5000);
        }
     }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed z-[9999] 
            sm:top-6 sm:right-6 sm:bottom-auto sm:left-auto sm:translate-x-0
            bottom-6 left-1/2 -translate-x-1/2 
            w-[calc(100%-2rem)] sm:w-auto sm:min-w-[320px] max-w-md"
     style="display: none;"
>
    <div class="relative bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden">
        <div class="p-4 flex items-center gap-4">
            <!-- Icon Box -->
            <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                {{ $type === 'success' ? 'bg-green-50 text-green-600' : '' }}
                {{ $type === 'error' ? 'bg-red-50 text-red-600' : '' }}
                {{ $type === 'warning' ? 'bg-yellow-50 text-yellow-600' : '' }}">
                
                @if($type === 'success')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @elseif($type === 'error')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @endif
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-800 uppercase tracking-wider">
                    {{ $type === 'success' ? 'Berhasil' : ($type === 'error' ? 'Kesalahan' : 'Peringatan') }}
                </p>
                <p class="text-sm text-slate-500 font-medium truncate">{{ $message }}</p>
            </div>

            <!-- Close Button -->
            <button @click="show = false" class="shrink-0 p-2 text-slate-300 hover:text-slate-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Progress Bar Indicator -->
        <div class="absolute bottom-0 left-0 h-1 w-full bg-slate-50">
            <div class="h-full animate-toast-progress
                {{ $type === 'success' ? 'bg-green-500' : '' }}
                {{ $type === 'error' ? 'bg-red-500' : '' }}
                {{ $type === 'warning' ? 'bg-yellow-500' : '' }}">
            </div>
        </div>
    </div>
</div>
@endif
