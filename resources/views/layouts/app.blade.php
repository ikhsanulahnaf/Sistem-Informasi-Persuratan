<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Persuratan') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Inter Font (Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js (untuk dropdown & modal interaktif) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .sidebar-bg {
            background-color: #0e2166;
        }

        .sidebar-item:hover {
            @apply bg-blue-700;
        }

        .sidebar-item.active {
            @apply bg-blue-800 border-l-4 border-yellow-400;
        }

        .card-shadow {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }

        [x-cloak] {
            display: none !important;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#f59e0b',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>

</head>

<body class="antialiased text-gray-800">

    <!-- Toast Notification (opsional) -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 z-50 px-6 py-3 bg-red-500 text-white rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        @auth
            <aside class="sidebar-bg text-white w-64 fixed h-full hidden md:block scrollbar-thin overflow-y-auto">
                <div class="p-5 border-b border-blue-900/30">
                    <h1 class="text-xl font-bold flex items-center gap-2">
                       <img src="{{ asset('images/logo-iti.png')}} " style="width: 20%;" alt="">
                        Sistem Persuratan
                    </h1>
                    <p class="text-blue-200 text-sm mt-1">ITI - {{ auth()->user()->unit ?? 'Admin' }}</p>
                </div>

                <nav class="p-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        data-aos="fade-right" data-aos-delay="100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    @if(auth()->user()->role === 'departemen')
                    <a href="{{ route('status-surat.index') }}"
                        class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('status-surat.*') ? 'active' : '' }}"
                        data-aos="fade-right" data-aos-delay="120">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Lihat Status Surat
                    </a>
                    @endif

                    <!-- Kelola Surat (Departemen) -->
                    <!-- Kelola Surat (Semua Role yang berhak) -->
                    <a href="{{ route('surat.index') }}"
                        class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('surat.*') ? 'active' : '' }}"
                        data-aos="fade-right" data-aos-delay="150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Kelola Surat
                    </a>

                    <!-- Approval WR - Paraf & Review -->
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('approval.adminTasks') }}"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('approval.adminTasks') ? 'active' : '' }}"
                            data-aos="fade-right" data-aos-delay="180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Antrian Penomoran
                        </a>
                    @endif

                    <!-- Approval WR - Paraf & Review -->
                    @if(auth()->user()->role === 'wakil_rektor')
                        <a href="{{ route('approval.pending') }}"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('approval.pending') ? 'active' : '' }}"
                            data-aos="fade-right" data-aos-delay="200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Approval (Paraf)
                        </a>
                    @endif

                    <!-- TTD Digital - Rektor -->
                    @if(auth()->user()->role === 'rektor')
                        <a href="{{ route('approval.waitingSignature') }}"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('approval.waitingSignature') ? 'active' : '' }}"
                            data-aos="fade-right" data-aos-delay="200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" />
                            </svg>
                            TTD Digital
                        </a>
                    @endif

                    <!-- Surat Masuk - Rektor -->
                    @if(auth()->user()->role === 'rektor')
                        <!-- <a href="{{ route('surat.index') }}?jenis=masuk"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition" data-aos="fade-right"
                            data-aos-delay="260">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            Surat Masuk
                        </a> -->
                    @endif

                    <!-- Arsip Surat - Lihat Arsip (WR, Rektor, Admin) -->
                    @if(in_array(auth()->user()->role, ['admin', 'rektor', 'wakil_rektor']))
                        <a href="{{ route('surat.arsip') }}"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('surat.arsip') ? 'active' : '' }}"
                            data-aos="fade-right" data-aos-delay="270">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4M5 8h14" />
                            </svg>
                            Arsip Surat
                        </a>
                    @endif

                    <!-- Manajemen User (hanya admin) -->
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('user.index') }}"
                            class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('user.*') ? 'active' : '' }}"
                            data-aos="fade-right" data-aos-delay="300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg> Kelola Pengguna
                        </a>
                    @endif

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="mt-8">
                        @csrf
                        <button type="submit"
                            class="sidebar-item flex items-center gap-3 w-full px-4 py-3 rounded-lg transition hover:bg-red-700"
                            data-aos="fade-right" data-aos-delay="320">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 ml-0 md:ml-64 p-4 md:p-8 min-h-screen bg-gray-50">
            @auth
                <!-- Header -->
                <header class="mb-8" data-aos="fade-down">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                            <p class="text-gray-600 mt-1">Selamat datang, <span
                                    class="font-semibold">{{ auth()->user()->name }}</span></p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="bg-white p-2 rounded-full shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div
                                class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 flex items-center justify-center text-gray-700 font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                </header>
            @endauth

            <!-- Page Content -->
            <div data-aos="fade-up" data-aos-delay="100">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Initialize AOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 600,
                once: true,
                easing: 'ease-out-cubic'
            });
        });
    </script>

    @livewireScripts
</body>

</html>