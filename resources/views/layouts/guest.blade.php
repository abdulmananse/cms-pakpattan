<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Official Complaint Management System for DC Pakpattan. Submit, track, and resolve public service complaints." />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-gray-800">
        <!-- Top Bar -->
        <div class="bg-primary-600 text-white text-sm">
            <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
            <p class="truncate">Government of the Punjab — District Pakpattan</p>
            <div class="flex items-center gap-6">
                <a href="#contact" class="hover:underline">Contact</a>
                <a href="tel:1718" class="font-semibold">Helpline: 1718</a>
            </div>
            </div>
        </div>

        <!-- Navbar -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-gray-100">
            <nav class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <!-- Placeholder Logo -->
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-100 text-primary-700 font-black">DC</span>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold">Deputy Commissioner</div>
                        <div class="text-xs text-gray-500 -mt-0.5">Pakpattan</div>
                    </div>
                </a>

                @if(Route::is('landing'))
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="hover:text-primary-700">Features</a>
                    <a href="#how" class="hover:text-primary-700">How it works</a>
                    <a href="#categories" class="hover:text-primary-700">Categories</a>
                    <a href="#track" class="hover:text-primary-700">Track</a>
                </div>
                @endif
                
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ url('login') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Login</a>
                    @endguest
                    @auth
                        <a href="{{ url('dashboard') }}" class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50">Dashboard</a>
                    @endauth
                    <a href="{{ url('complaint') }}" class="px-4 py-2 rounded-xl bg-primary-600 text-white hover:bg-primary-700">Register Complaint</a>
                </div>

                <!-- Mobile menu button -->
                <button id="menuBtn" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200">
                <span class="sr-only">Open menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div id="mobileMenu" class="md:hidden hidden border-t border-gray-100 py-3">
                <div class="flex flex-col gap-2">
                <a href="#features" class="px-2 py-2 rounded-lg hover:bg-gray-50">Features</a>
                <a href="#how" class="px-2 py-2 rounded-lg hover:bg-gray-50">How it works</a>
                <a href="#categories" class="px-2 py-2 rounded-lg hover:bg-gray-50">Categories</a>
                <a href="#track" class="px-2 py-2 rounded-lg hover:bg-gray-50">Track</a>
                <div class="h-px bg-gray-100 my-2"></div>
                    @guest
                        <a href="{{ url('register') }}" class="px-2 py-2 rounded-lg border border-gray-200 text-center">Register</a>
                    @endguest
                    @auth
                        <a href="{{ url('dashboard') }}" class="px-2 py-2 rounded-lg border border-gray-200 text-center">Dashboard</a>
                    @endauth
                    <a href="{{ url('complaint') }}" class="px-2 py-2 rounded-lg bg-primary-600 text-white text-center">Register Complaint</a>
                </div>
            </div>
            </nav>
        </header>

        {{ $slot }}
            
        <!-- Footer -->
        <footer id="contact" class="border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-primary-100 text-primary-700 font-black">DC</span>
                <div class="leading-tight">
                    <div class="text-sm font-semibold">Deputy Commissioner</div>
                    <div class="text-xs text-gray-500 -mt-0.5">Pakpattan</div>
                </div>
                </div>
                <p class="mt-4 text-sm text-gray-600 max-w-lg">Official portal for handling public service complaints. Together, we can make Pakpattan better for everyone.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold">Quick Links</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li><a href="#features" class="hover:text-primary-700">Features</a></li>
                <li><a href="#how" class="hover:text-primary-700">How it works</a></li>
                <li><a href="#categories" class="hover:text-primary-700">Categories</a></li>
                <li><a href="#track" class="hover:text-primary-700">Track Complaint</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold">Contact</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                <li>DC Office, Pakpattan</li>
                <li>Mon–Fri, 9:00–5:00</li>
                <li><a href="tel:+92457921020" class="hover:text-primary-700">+92 (457) 921020</a></li>
                <li><a href="mailto:info@dcpakpattan.gov.pk" class="hover:text-primary-700">info@dcpakpattan.gov.pk</a></li>
                </ul>
            </div>
            </div>
            <div class="border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>© <span id="year"></span> District Pakpattan — All rights reserved.</p>
                <p>Powered by CMS</p>
            </div>
            </div>
        </footer>
    </body>
</html>
