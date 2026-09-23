<x-app-layout>
    <div class="px-6 py-6 space-y-6 w-full" x-data="{
        viewMode: 'table',
        copiedSlug: null,
        copyToClipboard(slug) {
            navigator.clipboard.writeText(slug);
            this.copiedSlug = slug;
            setTimeout(() => { this.copiedSlug = null; }, 2000);
        }
    }">
        <!-- Top Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Categories
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                        {{ $totalCategories }} {{ Str::plural('Taxonomy', $totalCategories) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Organize your blog posts into distinct topics, manage taxonomy hierarchy, and monitor traffic distribution.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white text-xs font-semibold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/35 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Create Category</span>
                </button>
            </div>
        </div>

        <!-- KPI Metric Summary Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Categories -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalCategories }}</h3>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Active in production</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-500 dark:text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Posts Assigned -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Posts</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPosts }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Across all taxonomies</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Top Category -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 pr-2">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Top Category</p>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1 truncate" title="{{ $topCategory?->name ?? 'None' }}">
                            {{ $topCategory?->name ?? 'None yet' }}
                        </h3>
                        <p class="text-[11px] text-sky-600 dark:text-sky-400 font-medium mt-1">
                            {{ $topCategory?->posts_count ?? 0 }} {{ Str::plural('post', $topCategory?->posts_count ?? 0) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Empty / Uncategorized -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Empty Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $emptyCategoriesCount }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Categories with 0 posts</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 dark:text-purple-400 flex items-center justify-center border border-purple-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-dark-800/90 rounded-2xl p-4 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex-1 max-w-md relative">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Search by name, slug or description..."
                            class="w-full pl-9 pr-10 py-2 bg-gray-50 dark:bg-dark-900 border border-gray-200 dark:border-dark-600 rounded-xl text-xs text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                        @if(!empty($search))
                            <a href="{{ route('admin.categories.index', ['filter' => $filter]) }}"
                               class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Filter Tabs & View Toggle -->
                <div class="flex items-center justify-between sm:justify-end gap-2 overflow-x-auto">
                    <!-- Filter Pills -->
                    <div class="inline-flex p-1 bg-gray-100 dark:bg-dark-900 rounded-xl text-xs font-medium">
                        <a href="{{ route('admin.categories.index', ['filter' => 'all', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'all' ? 'bg-white dark:bg-dark-800 text-sky-600 dark:text-sky-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            All ({{ $totalCategories }})
                        </a>
                        <a href="{{ route('admin.categories.index', ['filter' => 'with_posts', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'with_posts' ? 'bg-white dark:bg-dark-800 text-sky-600 dark:text-sky-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            With Posts
                        </a>
                        <a href="{{ route('admin.categories.index', ['filter' => 'empty', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'empty' ? 'bg-white dark:bg-dark-800 text-sky-600 dark:text-sky-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            Empty ({{ $emptyCategoriesCount }})
                        </a>
                    </div>

                    <!-- View Switcher -->
                    <div class="hidden sm:inline-flex p-1 bg-gray-100 dark:bg-dark-900 rounded-xl text-gray-500 dark:text-gray-400">
                        <button type="button" @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white dark:bg-dark-800 text-sky-500 dark:text-sky-400 shadow-sm' : 'hover:text-gray-800 dark:hover:text-gray-200'"
                            class="p-1.5 rounded-lg transition" title="Table View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                        <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-white dark:bg-dark-800 text-sky-500 dark:text-sky-400 shadow-sm' : 'hover:text-gray-800 dark:hover:text-gray-200'"
                            class="p-1.5 rounded-lg transition" title="Grid View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside mt-1 ml-6 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Categories Presentation -->
        @if ($categories->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-dark-800/90 rounded-2xl border border-gray-200/80 dark:border-dark-700 p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center border border-sky-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">No categories found</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                    @if(!empty($search))
                        No category matches your search "{{ $search }}". Try refining your keywords or clearing the search.
                    @else
                        Get started by creating your first blog category to organize your content.
                    @endif
                </p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    @if(!empty($search))
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-4 py-2 rounded-xl border border-gray-300 dark:border-dark-600 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                            Clear Search
                        </a>
                    @endif
                    <button type="button" onclick="openCreateModal()"
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white text-xs font-semibold shadow hover:opacity-95 transition">
                        Create Category
                    </button>
                </div>
            </div>
        @else
            <!-- VIEW MODE 1: MODERN TABLE -->
            <div x-show="viewMode === 'table'" class="bg-white dark:bg-dark-800/90 rounded-2xl border border-gray-200/80 dark:border-dark-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-200/80 dark:border-dark-700 bg-gray-50/75 dark:bg-dark-900/60 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Category</th>
                                <th class="py-3.5 px-4">Slug</th>
                                <th class="py-3.5 px-4 text-center">Posts</th>
                                <th class="py-3.5 px-4">Created Date</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-700/60">
                            @foreach ($categories as $index => $category)
                                @php
                                    // Elegant category color palette cycle
                                    $colors = [
                                        ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-500', 'border' => 'border-sky-500/20'],
                                        ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-500', 'border' => 'border-indigo-500/20'],
                                        ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                                        ['bg' => 'bg-pink-500/10', 'text' => 'text-pink-500', 'border' => 'border-pink-500/20'],
                                        ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-500', 'border' => 'border-emerald-500/20'],
                                        ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                                    ];
                                    $c = $colors[$category->id % count($colors)];
                                @endphp
                                <tr class="hover:bg-gray-50/70 dark:hover:bg-dark-700/40 transition-colors group">
                                    <!-- Name & Avatar -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }} border flex items-center justify-center font-bold text-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                                {{ strtoupper(substr($category->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.categories.show', $category) }}"
                                                   class="font-semibold text-gray-900 dark:text-white group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors block truncate">
                                                    {{ $category->name }}
                                                </a>
                                                <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate max-w-xs mt-0.5">
                                                    {{ $category->description ?: 'No description provided.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Slug with 1-click Copy -->
                                    <td class="py-4 px-4">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-gray-600 dark:text-gray-300 font-mono text-[11px]">
                                            <span class="text-gray-400 dark:text-gray-500">/</span>
                                            <span class="font-medium">{{ $category->slug }}</span>
                                            <button type="button" @click="copyToClipboard('{{ $category->slug }}')"
                                                    class="text-gray-400 hover:text-sky-500 dark:hover:text-sky-400 ml-1 transition"
                                                    title="Copy slug">
                                                <svg x-show="copiedSlug !== '{{ $category->slug }}'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                <svg x-show="copiedSlug === '{{ $category->slug }}'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Posts Count -->
                                    <td class="py-4 px-4 text-center">
                                        @if($category->posts_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                                                {{ $category->posts_count }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-dark-700 text-gray-400 dark:text-gray-500">
                                                0 posts
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td class="py-4 px-4 text-gray-500 dark:text-gray-400 text-[11px]">
                                        {{ $category->created_at?->format('M d, Y') ?? '—' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <!-- View -->
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                               class="p-1.5 rounded-lg text-gray-400 hover:text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-500/10 transition"
                                               title="View Category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <button type="button" onclick="editCategory({{ $category->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition"
                                                title="Edit Category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Delete -->
                                            <button type="button" onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition"
                                                title="Delete Category">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-900/40">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

            <!-- VIEW MODE 2: CARD GRID -->
            <div x-show="viewMode === 'grid'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($categories as $category)
                        @php
                            $colors = [
                                ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-500', 'border' => 'border-sky-500/20'],
                                ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-500', 'border' => 'border-indigo-500/20'],
                                ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                                ['bg' => 'bg-pink-500/10', 'text' => 'text-pink-500', 'border' => 'border-pink-500/20'],
                                ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-500', 'border' => 'border-emerald-500/20'],
                                ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                            ];
                            $c = $colors[$category->id % count($colors)];
                        @endphp
                        <div class="relative group rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 p-5 shadow-sm hover:shadow-lg hover:border-sky-500/30 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }} border flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300">
                                        {{ $category->posts_count }} {{ Str::plural('Post', $category->posts_count) }}
                                    </span>
                                </div>

                                <a href="{{ route('admin.categories.show', $category) }}" class="block font-bold text-base text-gray-900 dark:text-white group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors">
                                    {{ $category->name }}
                                </a>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $category->description ?: 'No description provided.' }}
                                </p>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-dark-700 flex items-center justify-between">
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 dark:bg-dark-900 font-mono text-[10px] text-gray-500 dark:text-gray-400">
                                    /{{ $category->slug }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="p-1 rounded-md text-gray-400 hover:text-sky-500 transition" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="editCategory({{ $category->id }})" class="p-1 rounded-md text-gray-400 hover:text-indigo-500 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')" class="p-1 rounded-md text-gray-400 hover:text-rose-500 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($categories->hasPages())
                    <div class="p-4 bg-white dark:bg-dark-800/90 rounded-2xl border border-gray-200/80 dark:border-dark-700">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Create / Edit Modal with Live Slug Preview --}}
    <x-bladewind::modal name="category-modal" title="Create Category" ok_button_label="Save Category"
        cancel_button_label="Cancel" ok_button_action="submitCategoryModal()" center_action_buttons="false"
        backdrop_can_close="false" size="medium">

        <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="category-form-method" value="POST">
            <input type="hidden" name="category_id" id="category_id_field" value="{{ old('category_id', '') }}">

            <!-- Name Field -->
            <div>
                <x-bladewind::input name="name" id="name" label="Category Name" required="true"
                    error_message="Category name is required." show_error_inline="true"
                    selectedValue="{{ old('name', '') }}" placeholder="e.g. Artificial Intelligence, Web Design"
                    onkeyup="updateLiveSlug(this.value)" />
            </div>

            <!-- Live Slug Preview Tag -->
            <div class="px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-xs">
                <span class="text-gray-400">Public URL Preview:</span>
                <span class="font-mono text-sky-500 font-medium ml-1">
                    yourblog.com/categories/<span id="live-slug-text" class="underline decoration-dotted underline-offset-4 font-bold">new-category</span>
                </span>
            </div>

            <!-- Description Field -->
            <div>
                <x-bladewind::textarea name="description" id="description" label="Description (Optional)"
                    selectedValue="{{ old('description', '') }}"
                    placeholder="Brief description about the topic and what posts it encompasses..." rows="3"
                    onkeyup="updateDescCounter(this.value)" />
                <div class="text-[11px] text-gray-400 text-right mt-1">
                    <span id="desc-char-counter">0</span> / 1000 characters
                </div>
            </div>
        </form>
    </x-bladewind::modal>

    {{-- Modern Delete Confirmation Modal --}}
    <x-bladewind::modal type="error" name="delete-category-modal" title="Delete Category" ok_button_label="Confirm Delete"
        ok_button_action="submitDeleteCategoryModal()" cancel_button_label="Cancel" size="small">

        <div class="text-left py-2 space-y-2">
            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                Are you sure you want to permanently delete category
                <span id="delete-category-name" class="font-bold text-rose-500"></span>?
            </p>
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Posts currently belonging to this category will not be removed, but will become uncategorized.</span>
            </div>
        </div>

        <form id="delete-category-form" method="POST" action="" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-bladewind::modal>

    <script>
        const categories = @json($categoriesTableData);

        function slugify(text) {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        function updateLiveSlug(val) {
            const slug = slugify(val);
            const liveEl = document.getElementById('live-slug-text');
            if (liveEl) {
                liveEl.innerText = slug || 'category-slug';
            }
        }

        function updateDescCounter(val) {
            const countEl = document.getElementById('desc-char-counter');
            if (countEl) {
                countEl.innerText = val ? val.length : 0;
            }
        }

        function clearFormValidation() {
            const nameInput = domEl('#name');
            if (nameInput) {
                nameInput.classList.remove('has-error');
            }
            const errInline = domEl('.name-inline-error');
            if (errInline) {
                errInline.classList.add('hidden');
            }
        }

        function openCreateModal() {
            clearFormValidation();
            domEl('#category_id_field').value = '';
            domEl('#category-form-method').value = 'POST';
            domEl('#category-form').action = "{{ route('admin.categories.store') }}";
            domEl('#name').value = '';
            domEl('#description').value = '';
            updateLiveSlug('');
            updateDescCounter('');
            const titleEl = domEl('#category-modal-title');
            if (titleEl) titleEl.innerText = 'Create Category';
            showModal('category-modal');
        }

        function editCategory(id) {
            clearFormValidation();
            const cat = categories.find(c => String(c.id) === String(id));
            if (!cat) return;

            domEl('#category_id_field').value = cat.id;
            domEl('#category-form-method').value = 'PUT';
            domEl('#category-form').action = `/admin/categories/${cat.id}`;
            domEl('#name').value = cat.name;
            domEl('#description').value = cat.raw_description || '';
            updateLiveSlug(cat.slug || cat.name);
            updateDescCounter(cat.raw_description || '');
            const titleEl = domEl('#category-modal-title');
            if (titleEl) titleEl.innerText = 'Edit Category: ' + cat.name;
            showModal('category-modal');
        }

        function submitCategoryModal() {
            const name = domEl('#name')?.value?.trim();
            if (!name) {
                domEl('#name')?.focus();
                return;
            }
            domEl('#category-form').submit();
        }

        function deleteCategory(id, name) {
            domEl('#delete-category-form').action = `/admin/categories/${id}`;
            const nameEl = document.getElementById('delete-category-name');
            if (nameEl) nameEl.innerText = name;
            showModal('delete-category-modal');
        }

        function submitDeleteCategoryModal() {
            domEl('#delete-category-form').submit();
        }

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                const oldMethod = @json(old('_method', 'POST'));
                const oldId = @json(old('category_id'));
                if (oldMethod === 'PUT' && oldId) {
                    editCategory(oldId);
                } else {
                    openCreateModal();
                }
                domEl('#name').value = @json(old('name', ''));
                domEl('#description').value = @json(old('description', ''));
                updateLiveSlug(@json(old('name', '')));
                updateDescCounter(@json(old('description', '')));
            });
        @endif
    </script>
</x-app-layout>
