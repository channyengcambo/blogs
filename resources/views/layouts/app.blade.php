<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'My blog') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui-no-preflight.min.css') }}" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

@bladewindScripts('select', 'dropmenu')

<body class="font-sans antialiased dark bg-gray-100 dark:bg-dark-900 text-gray-800 dark:text-gray-100">
    <x-bladewind::notification />

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showNotification('Success', @json(session('success')), 'success');
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showNotification('Error', @json(session('error')), 'error');
            });
        </script>
    @endif

    <div class="flex h-screen bg-gray-100 dark:bg-dark-900 overflow-hidden font-sans">
        <!-- Sidebar -->
        <x-bladewind::sidebar name="admin-navigation" collapsible="true" active="{{ $activeSidebar }}">
            <x-slot:header>
                <div class="flex items-center gap-2.5 py-1">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-sky-500 via-indigo-500 to-cyan-400 flex items-center justify-center text-white shadow-md shadow-sky-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm tracking-wide text-gray-900 dark:text-white">Blog Admin</div>
                        <div class="text-[10px] text-gray-500 dark:text-gray-400 font-mono tracking-wider uppercase">Content Studio</div>
                    </div>
                </div>
            </x-slot:header>

            <x-bladewind::sidebar.group name="main" label="Management" expanded="true">
                <x-bladewind::sidebar.item name="dashboard" label="Dashboard" href="{{ route('admin.dashboard') }}"
                    icon="home" />
                <x-bladewind::sidebar.item name="categories" label="Categories"
                    href="{{ route('admin.categories.index') }}" icon="folder" />
                <x-bladewind::sidebar.item name="posts" label="Posts" href="{{ route('admin.posts.index') }}" icon="document-text" />
                <x-bladewind::sidebar.item name="tags" label="Tags" href="{{ route('admin.tags.index') }}" icon="tag" />
            </x-bladewind::sidebar.group>
        </x-bladewind::sidebar>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-100 dark:bg-dark-900">
            <!-- Top Navigation Bar -->
            <header class="h-16 flex-shrink-0 bg-white/90 dark:bg-dark-800/90 backdrop-blur-md border-b border-gray-200/80 dark:border-dark-700/80 px-6 flex items-center justify-between z-10">
                <!-- Left: Breadcrumb / Context -->
                <div class="flex items-center gap-3">
                    <nav class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-500 dark:hover:text-sky-400 transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <svg class="w-3 h-3 mx-1 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium text-gray-800 dark:text-gray-200 capitalize">
                            {{ $activeSidebar ?? 'Management' }}
                        </span>
                    </nav>

                    <span class="hidden sm:inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Admin Mode
                    </span>
                </div>

                <!-- Right: Quick actions & User menu -->
                <div class="flex items-center gap-3">
                    <!-- Notification Bell -->
                    <button type="button" class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-dark-700 transition" title="Notifications">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-sky-500 ring-2 ring-white dark:ring-dark-800"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                        <button @click="open = !open" type="button" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-lg border border-gray-200/80 dark:border-dark-700 hover:bg-gray-50 dark:hover:bg-dark-700 transition text-left focus:outline-none focus:ring-2 focus:ring-sky-500/30">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold uppercase shadow-sm">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="hidden sm:block">
                                <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                    {{ auth()->user()->name ?? 'Admin' }}
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400">Super Administrator</div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-52 rounded-xl bg-white dark:bg-dark-800 border border-gray-200/80 dark:border-dark-700 shadow-xl py-1.5 z-50 divide-y divide-gray-100 dark:divide-dark-700 text-xs"
                             style="display: none;">
                            <div class="px-3.5 py-2">
                                <p class="text-gray-500 dark:text-gray-400 text-[11px]">Signed in as</p>
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3.5 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-dark-700 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3.5 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition text-left">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50/50 dark:bg-dark-900 min-h-0">
                @if (isset($header))
                    <div class="bg-white dark:bg-dark-800 border-b border-gray-200/80 dark:border-dark-700 px-6 py-4">
                        {{ $header }}
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
