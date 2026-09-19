<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@bladewindScripts('select', 'dropmenu')

<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-bladewind::sidebar name="admin-navigation" collapsible="true" active="dashboard">
            <x-slot:header>My Blog Admin</x-slot:header>

            <x-bladewind::sidebar.group name="main" label="Management" expanded="true">
                <x-bladewind::sidebar.item name="dashboard" label="Dashboard" href="{{ route('admin.dashboard') }}"
                    icon="home" />
                <x-bladewind::sidebar.item name="categories" label="Categories" href="#" icon="folder" />
                <x-bladewind::sidebar.item name="posts" label="Posts" href="#" icon="document-text" />
                <x-bladewind::sidebar.item name="tags" label="Tags" href="#" icon="tag" />
            </x-bladewind::sidebar.group>
        </x-bladewind::sidebar>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
