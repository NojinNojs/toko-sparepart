<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Toko Sparepart Otomotif') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased font-sans">
        <div x-data="{ isSubmitting: false }" 
             @submit="if (!$event.defaultPrevented) isSubmitting = true"
             class="min-h-screen flex flex-col relative">
            
            <!-- Invisible Click Shield -->
            <div x-show="isSubmitting" class="click-shield" style="display: none;"></div>

            {{-- Flash Messages --}}
            <x-flash-message />

            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow border-b border-slate-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 mt-auto">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <x-brand-logo size="sm" />
                    </div>
                    <p class="text-slate-500 text-sm text-center md:text-left">
                        &copy; {{ date('Y') }} Toko Sparepart Otomotif UKK. Hak cipta dilindungi undang-undang.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
