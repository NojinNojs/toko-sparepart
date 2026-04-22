<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel' }} — Toko Sparepart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-hidden">
    <div x-data="{ sidebarOpen: false, isSubmitting: false }" 
         @submit="if (!$event.defaultPrevented) isSubmitting = true"
         class="flex h-screen overflow-hidden">
        
        <!-- Invisible Click Shield -->
        <div x-show="isSubmitting" class="click-shield" style="display: none;"></div>

        <x-flash-message />

        <!-- === SIDEBAR === -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-sm">
            <!-- Brand -->
            <div class="flex items-center justify-center h-16 bg-white border-b border-slate-200 px-4">
                <a href="{{ route('admin.dashboard') }}">
                    <x-brand-logo size="sm" />
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">
                <p class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Kelola</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.produk.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.produk.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.produk.*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Produk
                </a>

                <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.kategori.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kategori.*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>

                <a href="{{ route('admin.brand.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.brand.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.brand.*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Merek
                </a>

                <p class="px-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-6 mb-2">Penjualan</p>

                <a href="{{ route('admin.pembelian.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.pembelian.*') ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.pembelian.*') ? 'text-primary-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Transaksi
                </a>
            </nav>

            <!-- User Info + Logout -->
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                <div class="flex items-center gap-3 px-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Admin</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden" style="display: none;"></div>

        <!-- === MAIN CONTENT === -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
            
            <!-- Topbar -->
            <header class="h-14 sm:h-16 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 z-10 shadow-sm">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="p-2 mr-2 -ml-1 text-slate-500 rounded-lg lg:hidden hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <!-- Header Slot -->
                    @isset($header)
                        <h1 class="text-base sm:text-xl font-bold text-slate-800 tracking-tight truncate">{{ $header }}</h1>
                    @endisset
                </div>

                <div class="flex items-center gap-2 sm:gap-4 text-sm font-medium text-slate-600">
                    {{-- Admin avatar for mobile, full name for desktop --}}
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm text-slate-600 font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('home') }}" class="btn-secondary py-1.5 px-3 text-xs hidden sm:flex" title="Lihat Website">
                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span class="hidden sm:inline">Website</span>
                    </a>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-3 sm:p-6 lg:p-8">
                <!-- Page Content -->
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
