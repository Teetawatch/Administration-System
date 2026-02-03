<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ระบบงานธุรการ') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased" x-data="{ collapsed: false, sidebarOpen: false }">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="transition-all duration-300" :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
            <!-- Top Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Mobile Menu Button (Hamburger) -->
                    <button type="button" @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden flex items-center justify-center w-12 h-12 -ml-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 active:bg-gray-200 active:scale-95 transition-all duration-200"
                        aria-label="เปิด/ปิดเมนู" :aria-expanded="sidebarOpen">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    @if (isset($header))
                        <div class="flex-1 lg:flex-none">
                            {{ $header }}
                        </div>
                    @endif

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Notifications -->
                        <button
                            class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors relative">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Current Date -->
                        <div
                            class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-gray-600">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>{{ now()->locale('th')->translatedFormat('j F Y') }}</span>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative ml-2" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span
                                    class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 origin-top-right"
                                style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">บัญชีผู้ใช้</p>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                    ข้อมูลส่วนตัว
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 flex items-center gap-2">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });

        // Re-initialize icons after Livewire updates
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>

    <!-- Alpine Store for Sidebar is defined in app.js -->

    <!-- Livewire Scripts (Manual for subfolder /adm) -->
    <script>
        window.livewireScriptConfig = {
            "csrf": "{{ csrf_token() }}",
            "uri": "/adm/livewire/update",
            "progressBar": true,
            "nonce": ""
        };
    </script>
    <script src="https://nass.ac.th/adm/livewire/livewire.js" data-csrf="{{ csrf_token() }}"
        data-update-uri="/adm/livewire/update" data-navigate-once="true"
        onload="if(window.Livewire && !Livewire.started){ Livewire.start(); }"></script>
</body>

</html>