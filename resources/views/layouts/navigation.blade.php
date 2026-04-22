<nav x-data="{ open: false }" class="bg-white sticky top-0 z-50 transition-all duration-300 border-b border-slate-100 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-20"> {{-- Increased height for premium feel --}}
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-brand-logo size="sm" class="sm:hidden" />
                        <x-brand-logo size="md" class="hidden sm:flex" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-12 sm:flex items-center">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-600 shadow-sm shadow-primary-500/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Beranda
                    </a>
                    
                    @auth
                        @if(auth()->user()->isCustomer())
                            <a href="{{ route('pembelian.index') }}" 
                               class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('pembelian.index') ? 'bg-primary-50 text-primary-600 shadow-sm shadow-primary-500/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Pesanan Saya
                            </a>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-primary-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Admin Dashboard
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 p-1.5 pr-3 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-primary-100 hover:shadow-sm transition-all duration-300 group">
                                <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-primary-500/20 group-hover:rotate-12 transition-transform">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-medium text-slate-500 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 ms-1 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-50">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Akun</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-primary-600 font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary-600 px-4 py-2 rounded-xl transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:-translate-y-0.5 transition-all">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-primary-50 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak
         class="sm:hidden bg-white border-t border-slate-100 absolute w-full left-0 shadow-2xl rounded-b-3xl">
        <div class="pt-4 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="flex items-center">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </x-responsive-nav-link>
            
            @auth
                @if(auth()->user()->isCustomer())
                    <x-responsive-nav-link :href="route('pembelian.index')" :active="request()->routeIs('pembelian.index')" class="flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Pesanan Saya
                    </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" class="text-primary-600 font-bold flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Admin Dashboard
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-slate-100 bg-slate-50/50 rounded-b-3xl">
                <div class="px-5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-bold shadow-lg shadow-primary-500/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                        <svg class="w-4 h-4 mr-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profil Saya
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-primary-600 font-bold flex items-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
             <div class="p-4 space-y-2 border-t border-slate-100 rounded-b-3xl">
                <a href="{{ route('login') }}" class="block w-full text-center py-3 font-bold text-slate-600 bg-slate-50 rounded-xl transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="block w-full text-center py-3 font-bold text-white bg-primary-600 rounded-xl shadow-lg shadow-primary-500/30 transition-all">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
