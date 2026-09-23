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
                        Tags
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                        {{ $totalTags }} {{ Str::plural('Keyword', $totalTags) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Manage keywords, taxonomy metadata, and fine-grained labels to help readers discover related topics.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-indigo-400 hover:to-pink-400 text-white text-xs font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/35 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Create Tag</span>
                </button>
            </div>
        </div>

        <!-- KPI Metric Summary Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Tags -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Tags</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalTags }}</h3>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Active indexing labels</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-500 dark:text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Posts Linked -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Posts</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPosts }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Available blog posts</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-500 dark:text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Top Tag -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 pr-2">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Top Tag</p>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1 truncate" title="{{ $topTag?->name ?? 'None' }}">
                            {{ $topTag?->name ?? 'None yet' }}
                        </h3>
                        <p class="text-[11px] text-purple-600 dark:text-purple-400 font-medium mt-1">
                            {{ $topTag?->posts_count ?? 0 }} {{ Str::plural('post', $topTag?->posts_count ?? 0) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Empty / Unused Tags -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Empty Tags</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $emptyTagsCount }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Tags with 0 posts</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 dark:text-rose-400 flex items-center justify-center border border-rose-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4zM6 18L18 6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white dark:bg-dark-800/90 rounded-2xl p-4 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.tags.index') }}" class="flex-1 max-w-md relative">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Search tags by keyword or slug..."
                            class="w-full pl-9 pr-10 py-2 bg-gray-50 dark:bg-dark-900 border border-gray-200 dark:border-dark-600 rounded-xl text-xs text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition">
                        @if(!empty($search))
                            <a href="{{ route('admin.tags.index', ['filter' => $filter]) }}"
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
                        <a href="{{ route('admin.tags.index', ['filter' => 'all', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'all' ? 'bg-white dark:bg-dark-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            All ({{ $totalTags }})
                        </a>
                        <a href="{{ route('admin.tags.index', ['filter' => 'with_posts', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'with_posts' ? 'bg-white dark:bg-dark-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            With Posts
                        </a>
                        <a href="{{ route('admin.tags.index', ['filter' => 'empty', 'search' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'empty' ? 'bg-white dark:bg-dark-800 text-indigo-600 dark:text-indigo-400 shadow-sm font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200' }}">
                            Empty ({{ $emptyTagsCount }})
                        </a>
                    </div>

                    <!-- View Switcher -->
                    <div class="hidden sm:inline-flex p-1 bg-gray-100 dark:bg-dark-900 rounded-xl text-gray-500 dark:text-gray-400">
                        <button type="button" @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white dark:bg-dark-800 text-indigo-500 dark:text-indigo-400 shadow-sm' : 'hover:text-gray-800 dark:hover:text-gray-200'"
                            class="p-1.5 rounded-lg transition" title="Table View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                        <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-white dark:bg-dark-800 text-indigo-500 dark:text-indigo-400 shadow-sm' : 'hover:text-gray-800 dark:hover:text-gray-200'"
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

        <!-- Main Tags Presentation -->
        @if ($tags->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-dark-800/90 rounded-2xl border border-gray-200/80 dark:border-dark-700 p-12 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center border border-indigo-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">No tags found</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                    @if(!empty($search))
                        No tags match your search keyword "{{ $search }}". Try clearing the search.
                    @else
                        Get started by adding your first blog tag to label articles.
                    @endif
                </p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    @if(!empty($search))
                        <a href="{{ route('admin.tags.index') }}"
                            class="px-4 py-2 rounded-xl border border-gray-300 dark:border-dark-600 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                            Clear Search
                        </a>
                    @endif
                    <button type="button" onclick="openCreateModal()"
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-semibold shadow hover:opacity-95 transition">
                        Create Tag
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
                                <th class="py-3.5 px-5">Tag / Keyword</th>
                                <th class="py-3.5 px-4">Slug</th>
                                <th class="py-3.5 px-4 text-center">Tagged Posts</th>
                                <th class="py-3.5 px-4">Created Date</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-700/60">
                            @foreach ($tags as $tag)
                                @php
                                    $colors = [
                                        ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-500', 'border' => 'border-indigo-500/20'],
                                        ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                                        ['bg' => 'bg-pink-500/10', 'text' => 'text-pink-500', 'border' => 'border-pink-500/20'],
                                        ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-500', 'border' => 'border-sky-500/20'],
                                        ['bg' => 'bg-teal-500/10', 'text' => 'text-teal-500', 'border' => 'border-teal-500/20'],
                                        ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                                    ];
                                    $c = $colors[$tag->id % count($colors)];
                                @endphp
                                <tr class="hover:bg-gray-50/70 dark:hover:bg-dark-700/40 transition-colors group">
                                    <!-- Name & Tag Icon -->
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }} border flex items-center justify-center font-bold text-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.tags.show', $tag) }}"
                                                   class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors block truncate">
                                                    {{ $tag->name }}
                                                </a>
                                                <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate max-w-xs mt-0.5">
                                                    {{ $tag->description ?: 'No description provided.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Slug with 1-click Copy -->
                                    <td class="py-4 px-4">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-gray-600 dark:text-gray-300 font-mono text-[11px]">
                                            <span class="text-gray-400 dark:text-gray-500">/</span>
                                            <span class="font-medium">{{ $tag->slug }}</span>
                                            <button type="button" @click="copyToClipboard('{{ $tag->slug }}')"
                                                    class="text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 ml-1 transition"
                                                    title="Copy slug">
                                                <svg x-show="copiedSlug !== '{{ $tag->slug }}'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                <svg x-show="copiedSlug === '{{ $tag->slug }}'" class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Tagged Posts Count -->
                                    <td class="py-4 px-4 text-center">
                                        @if($tag->posts_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                                {{ $tag->posts_count }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-dark-700 text-gray-400 dark:text-gray-500">
                                                0 posts
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td class="py-4 px-4 text-gray-500 dark:text-gray-400 text-[11px]">
                                        {{ $tag->created_at?->format('M d, Y') ?? '—' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <!-- View -->
                                            <a href="{{ route('admin.tags.show', $tag) }}"
                                               class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition"
                                               title="View Tag Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <button type="button" onclick="editTag({{ $tag->id }})"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition"
                                                title="Edit Tag">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Delete -->
                                            <button type="button" onclick="deleteTag({{ $tag->id }}, '{{ addslashes($tag->name) }}')"
                                                class="p-1.5 rounded-lg text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition"
                                                title="Delete Tag">
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

                @if ($tags->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-900/40">
                        {{ $tags->links() }}
                    </div>
                @endif
            </div>

            <!-- VIEW MODE 2: CARD GRID -->
            <div x-show="viewMode === 'grid'" class="space-y-6" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($tags as $tag)
                        @php
                            $colors = [
                                ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-500', 'border' => 'border-indigo-500/20'],
                                ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                                ['bg' => 'bg-pink-500/10', 'text' => 'text-pink-500', 'border' => 'border-pink-500/20'],
                                ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-500', 'border' => 'border-sky-500/20'],
                                ['bg' => 'bg-teal-500/10', 'text' => 'text-teal-500', 'border' => 'border-teal-500/20'],
                                ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                            ];
                            $c = $colors[$tag->id % count($colors)];
                        @endphp
                        <div class="relative group rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 p-5 shadow-sm hover:shadow-lg hover:border-indigo-500/30 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} {{ $c['border'] }} border flex items-center justify-center font-bold text-sm">
                                        #
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300">
                                        {{ $tag->posts_count }} {{ Str::plural('Post', $tag->posts_count) }}
                                    </span>
                                </div>

                                <a href="{{ route('admin.tags.show', $tag) }}" class="block font-bold text-base text-gray-900 dark:text-white group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $tag->name }}
                                </a>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $tag->description ?: 'No description provided for this tag.' }}
                                </p>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-dark-700 flex items-center justify-between">
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 dark:bg-dark-900 font-mono text-[10px] text-gray-500 dark:text-gray-400">
                                    /{{ $tag->slug }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.tags.show', $tag) }}" class="p-1 rounded-md text-gray-400 hover:text-indigo-500 transition" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="editTag({{ $tag->id }})" class="p-1 rounded-md text-gray-400 hover:text-purple-500 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deleteTag({{ $tag->id }}, '{{ addslashes($tag->name) }}')" class="p-1 rounded-md text-gray-400 hover:text-rose-500 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($tags->hasPages())
                    <div class="p-4 bg-white dark:bg-dark-800/90 rounded-2xl border border-gray-200/80 dark:border-dark-700">
                        {{ $tags->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Create / Edit Modal with Live Slug Preview --}}
    <x-bladewind::modal name="tag-modal" title="Create Tag" ok_button_label="Save Tag"
        cancel_button_label="Cancel" ok_button_action="submitTagModal()" center_action_buttons="false"
        backdrop_can_close="false" size="medium">

        <form id="tag-form" method="POST" action="{{ route('admin.tags.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="tag-form-method" value="POST">
            <input type="hidden" name="tag_id" id="tag_id_field" value="{{ old('tag_id', '') }}">

            <!-- Name Field -->
            <div>
                <x-bladewind::input name="name" id="tag_name" label="Tag Name" required="true"
                    error_message="Tag name is required." show_error_inline="true"
                    selectedValue="{{ old('name', '') }}" placeholder="e.g. Laravel, React, Tailwind, Docker"
                    onkeyup="updateLiveSlug(this.value)" />
            </div>

            <!-- Live Slug Preview Tag -->
            <div class="px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-xs">
                <span class="text-gray-400">Public URL Preview:</span>
                <span class="font-mono text-indigo-500 font-medium ml-1">
                    yourblog.com/tags/<span id="live-slug-text" class="underline decoration-dotted underline-offset-4 font-bold">new-tag</span>
                </span>
            </div>

            <!-- Description Field -->
            <div>
                <x-bladewind::textarea name="description" id="tag_description" label="Description (Optional)"
                    selectedValue="{{ old('description', '') }}"
                    placeholder="Brief description about the topic covered by this tag..." rows="3"
                    onkeyup="updateDescCounter(this.value)" />
                <div class="text-[11px] text-gray-400 text-right mt-1">
                    <span id="desc-char-counter">0</span> / 1000 characters
                </div>
            </div>
        </form>
    </x-bladewind::modal>

    {{-- Modern Delete Confirmation Modal --}}
    <x-bladewind::modal type="error" name="delete-tag-modal" title="Delete Tag" ok_button_label="Confirm Delete"
        ok_button_action="submitDeleteTagModal()" cancel_button_label="Cancel" size="small">

        <div class="text-left py-2 space-y-2">
            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                Are you sure you want to permanently delete tag
                <span id="delete-tag-name" class="font-bold text-rose-500"></span>?
            </p>
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs flex items-start gap-2">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Posts currently tagged with this term will remain intact, and this tag will simply be unlinked.</span>
            </div>
        </div>

        <form id="delete-tag-form" method="POST" action="" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-bladewind::modal>

    <script>
        const tags = @json($tagsTableData);

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
                liveEl.innerText = slug || 'tag-slug';
            }
        }

        function updateDescCounter(val) {
            const countEl = document.getElementById('desc-char-counter');
            if (countEl) {
                countEl.innerText = val ? val.length : 0;
            }
        }

        function clearFormValidation() {
            const nameInput = domEl('#tag_name');
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
            domEl('#tag_id_field').value = '';
            domEl('#tag-form-method').value = 'POST';
            domEl('#tag-form').action = "{{ route('admin.tags.store') }}";
            domEl('#tag_name').value = '';
            domEl('#tag_description').value = '';
            updateLiveSlug('');
            updateDescCounter('');
            const titleEl = domEl('#tag-modal-title');
            if (titleEl) titleEl.innerText = 'Create Tag';
            showModal('tag-modal');
        }

        function editTag(id) {
            clearFormValidation();
            const tag = tags.find(t => String(t.id) === String(id));
            if (!tag) return;

            domEl('#tag_id_field').value = tag.id;
            domEl('#tag-form-method').value = 'PUT';
            domEl('#tag-form').action = `/admin/tags/${tag.id}`;
            domEl('#tag_name').value = tag.name;
            domEl('#tag_description').value = tag.raw_description || '';
            updateLiveSlug(tag.slug || tag.name);
            updateDescCounter(tag.raw_description || '');
            const titleEl = domEl('#tag-modal-title');
            if (titleEl) titleEl.innerText = 'Edit Tag: ' + tag.name;
            showModal('tag-modal');
        }

        function submitTagModal() {
            const name = domEl('#tag_name')?.value?.trim();
            if (!name) {
                domEl('#tag_name')?.focus();
                return;
            }
            domEl('#tag-form').submit();
        }

        function deleteTag(id, name) {
            domEl('#delete-tag-form').action = `/admin/tags/${id}`;
            const nameEl = document.getElementById('delete-tag-name');
            if (nameEl) nameEl.innerText = name;
            showModal('delete-tag-modal');
        }

        function submitDeleteTagModal() {
            domEl('#delete-tag-form').submit();
        }

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', () => {
                const oldMethod = @json(old('_method', 'POST'));
                const oldId = @json(old('tag_id'));
                if (oldMethod === 'PUT' && oldId) {
                    editTag(oldId);
                } else {
                    openCreateModal();
                }
                domEl('#tag_name').value = @json(old('name', ''));
                domEl('#tag_description').value = @json(old('description', ''));
                updateLiveSlug(@json(old('name', '')));
                updateDescCounter(@json(old('description', '')));
            });
        @endif
    </script>
</x-app-layout>
