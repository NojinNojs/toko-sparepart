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
    <body class="font-sans text-slate-800 antialiased bg-slate-50 flex flex-col min-h-screen">
        <div x-data="{ isSubmitting: false }" 
             @submit="if (!$event.defaultPrevented) isSubmitting = true"
             class="min-h-screen flex flex-col relative px-4">
            
            <!-- Invisible Click Shield -->
            <div x-show="isSubmitting" class="click-shield" style="display: none;"></div>

            <x-flash-message />

        {{-- Simple Header for Auth pages --}}
        <header class="bg-white border-b border-slate-200 py-4 absolute w-full top-0">
            <div class="max-w-7xl mx-auto px-4 flex justify-center">
                <a href="{{ route('home') }}">
                    <x-brand-logo size="md" />
                </a>
            </div>
        </header>

        <div class="flex-grow flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-card border border-slate-200">
                {{ $slot }}
            </div>
        </div>
    </div>
    </body>
</html>
