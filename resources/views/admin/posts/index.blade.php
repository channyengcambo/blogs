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
                        Posts
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                        {{ $totalPosts }} {{ Str::plural('Article', $totalPosts) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Zero-Join Fast Lookup
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    High-performance data table for blog management. Denormalized category names and tag caches enable instantaneous reads.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.posts.index', array_merge(request()->query(), ['format' => 'json'])) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-dark-800 border border-gray-200 dark:border-dark-700 text-gray-700 dark:text-gray-300 hover:text-sky-500 dark:hover:text-sky-400 text-xs font-medium shadow-sm transition hover:border-sky-500/50">
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <span>JSON Feed API</span>
                </a>
            </div>
        </div>

        <!-- KPI Metric Summary Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Posts -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Articles</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPosts }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Full content archive</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-500 dark:text-sky-400 flex items-center justify-center border border-sky-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Published Articles -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Published</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $publishedPosts }}</h3>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ $totalPosts > 0 ? round(($publishedPosts / $totalPosts) * 100) : 0 }}% live & public</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Drafts -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Drafts</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $draftPosts }}</h3>
                        <p class="text-[11px] text-amber-600 dark:text-amber-400 font-medium mt-1">Pending review & release</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Total Reader Views -->
            <div class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Views</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalViews) }}</h3>
                        <p class="text-[11px] text-purple-600 dark:text-purple-400 font-medium mt-1">Cumulative engagements</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center border border-purple-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar & Filter Strip -->
        <div class="p-4 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Search input & Category dropdown -->
                <form method="GET" action="{{ route('admin.posts.index') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">

                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Search by title, subtitle, slug, or author..."
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-gray-50 dark:bg-dark-900 border border-gray-200 dark:border-dark-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                        @if ($search)
                            <a href="{{ route('admin.posts.index', ['status' => $status, 'category_id' => $categoryId, 'sort' => $sort]) }}"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <!-- Category Filter Dropdown -->
                    <div class="w-full sm:w-56">
                        <select name="category_id" onchange="this.form.submit()"
                            class="w-full py-2 pl-3 pr-8 rounded-xl text-xs bg-gray-50 dark:bg-dark-900 border border-gray-200 dark:border-dark-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="w-full sm:w-44">
                        <select name="sort" onchange="this.form.submit()"
                            class="w-full py-2 pl-3 pr-8 rounded-xl text-xs bg-gray-50 dark:bg-dark-900 border border-gray-200 dark:border-dark-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition">
                            <option value="latest" @selected($sort === 'latest')>Newest First</option>
                            <option value="views" @selected($sort === 'views')>Most Viewed</option>
                            <option value="title" @selected($sort === 'title')>Title (A-Z)</option>
                            <option value="oldest" @selected($sort === 'oldest')>Oldest First</option>
                        </select>
                    </div>

                    <button type="submit" class="hidden sm:inline-flex items-center px-3 py-2 rounded-xl bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-600 text-xs font-medium transition">
                        Filter
                    </button>
                </form>

                <!-- View Mode Switcher -->
                <div class="flex items-center justify-between sm:justify-end gap-2 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-100 dark:border-dark-700">
                    <div class="inline-flex p-1 rounded-xl bg-gray-100 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700">
                        <button type="button" @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white dark:bg-dark-800 text-sky-600 dark:text-sky-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Table</span>
                        </button>
                        <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-white dark:bg-dark-800 text-sky-600 dark:text-sky-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Grid</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="flex items-center gap-2 border-t border-gray-100 dark:border-dark-700 pt-3 overflow-x-auto">
                <a href="{{ route('admin.posts.index', ['status' => 'all', 'category_id' => $categoryId, 'search' => $search, 'sort' => $sort]) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ $status === 'all' || empty($status) ? 'bg-sky-500/10 text-sky-600 dark:text-sky-400 font-semibold border border-sky-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-700' }}">
                    All Posts ({{ $totalPosts }})
                </a>
                <a href="{{ route('admin.posts.index', ['status' => 'published', 'category_id' => $categoryId, 'search' => $search, 'sort' => $sort]) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'published' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-semibold border border-emerald-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Published ({{ $publishedPosts }})
                </a>
                <a href="{{ route('admin.posts.index', ['status' => 'draft', 'category_id' => $categoryId, 'search' => $search, 'sort' => $sort]) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap flex items-center gap-1.5 {{ $status === 'draft' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold border border-amber-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Drafts ({{ $draftPosts }})
                </a>
            </div>
        </div>

        @if ($posts->isEmpty())
            <!-- Empty State -->
            <div class="p-12 text-center rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">No articles found</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                    @if ($search || $status !== 'all' || $categoryId)
                        No articles match the specified filters. Try adjusting your query or resetting filters.
                    @else
                        No posts have been published yet. Run the database seeders to populate demo content.
                    @endif
                </p>
                @if ($search || $status !== 'all' || $categoryId)
                    <div class="mt-4">
                        <a href="{{ route('admin.posts.index') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-600 text-xs font-medium transition">
                            Reset all filters
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- VIEW 1: HIGH PERFORMANCE DATA TABLE -->
            <div x-show="viewMode === 'table'" class="overflow-hidden rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm transition">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-200/80 dark:border-dark-700 bg-gray-50/75 dark:bg-dark-900/60 text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 pl-6 pr-4">Article</th>
                                <th class="py-3.5 px-4">Category</th>
                                <th class="py-3.5 px-4">Taxonomy Tags (Cache)</th>
                                <th class="py-3.5 px-4">Status & Workflow</th>
                                <th class="py-3.5 px-4">Metrics</th>
                                <th class="py-3.5 px-4">Author</th>
                                <th class="py-3.5 pl-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-700/60 text-gray-700 dark:text-gray-300">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-dark-700/40 transition-colors group">
                                    <!-- Article Info & Thumbnail -->
                                    <td class="py-4 pl-6 pr-4">
                                        <div class="flex items-start gap-3.5 max-w-md">
                                            @if ($post->featured_image)
                                                <img src="{{ $post->featured_image }}" alt=""
                                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=300&q=80';"
                                                    class="w-14 h-14 rounded-xl object-cover border border-gray-200/80 dark:border-dark-700 flex-shrink-0 shadow-sm">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-tr from-sky-500/20 to-indigo-500/20 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-base flex-shrink-0 border border-sky-500/20">
                                                    {{ Str::substr($post->title, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0 flex-1">
                                                <a href="{{ route('admin.posts.show', $post) }}"
                                                    class="font-semibold text-gray-900 dark:text-white hover:text-sky-500 dark:hover:text-sky-400 transition text-sm line-clamp-1">
                                                    {{ $post->title }}
                                                </a>
                                                @if ($post->sub_title)
                                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">
                                                        {{ $post->sub_title }}
                                                    </p>
                                                @endif
                                                <div class="flex items-center gap-2 mt-1.5">
                                                    <button type="button" @click="copyToClipboard('{{ $post->slug }}')"
                                                        class="inline-flex items-center gap-1 font-mono text-[10px] text-gray-400 hover:text-sky-500 dark:hover:text-sky-400 transition"
                                                        title="Click to copy slug">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span>/{{ Str::limit($post->slug, 28) }}</span>
                                                        <span x-show="copiedSlug === '{{ $post->slug }}'" x-cloak class="text-[10px] text-emerald-500 font-semibold font-sans">Copied!</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Denormalized Category -->
                                    <td class="py-4 px-4 align-top">
                                        @if ($post->category_name)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-medium bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                                                {{ $post->category_name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Uncategorized</span>
                                        @endif
                                    </td>

                                    <!-- Denormalized Tags Cache (Zero Joins!) -->
                                    <td class="py-4 px-4 align-top">
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @if (!empty($post->tags_cache) && is_array($post->tags_cache))
                                                @foreach (array_slice($post->tags_cache, 0, 3) as $tag)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                                        #{{ $tag['name'] ?? $tag['slug'] }}
                                                    </span>
                                                @endforeach
                                                @if (count($post->tags_cache) > 3)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] text-gray-500 bg-gray-100 dark:bg-dark-700" title="{{ count($post->tags_cache) - 3 }} more tags">
                                                        +{{ count($post->tags_cache) - 3 }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400 text-[11px] italic">No tags</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status & 1-Click Status Toggle -->
                                    <td class="py-4 px-4 align-top">
                                        <div class="space-y-1.5">
                                            @if ($post->status === 'published')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Published
                                                </span>
                                                <p class="text-[10px] text-gray-400">
                                                    {{ $post->published_at?->format('M d, Y') ?? 'Recently' }}
                                                </p>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Draft
                                                </span>
                                            @endif

                                            <!-- 1-Click Status Toggle Form -->
                                            <form method="POST" action="{{ route('admin.posts.toggle-status', $post) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-[10px] font-medium text-gray-500 hover:text-sky-600 dark:hover:text-sky-400 flex items-center gap-1 transition underline decoration-dotted">
                                                    <span>{{ $post->status === 'published' ? 'Switch to Draft' : 'Publish Article' }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <!-- Performance Metrics Counters -->
                                    <td class="py-4 px-4 align-top">
                                        <div class="space-y-1 text-xs">
                                            <div class="flex items-center gap-1.5 font-medium text-gray-900 dark:text-gray-100">
                                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>{{ number_format($post->views_count) }} views</span>
                                            </div>
                                            <div class="flex items-center gap-3 text-[11px] text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center gap-1" title="{{ $post->likes_count }} likes">
                                                    <svg class="w-3 h-3 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $post->likes_count }}
                                                </span>
                                                <span class="flex items-center gap-1" title="{{ $post->comments_count }} comments">
                                                    <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    {{ $post->comments_count }}
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-gray-400 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ $post->reading_time ?? 1 }} min read</span>
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Author & Timestamp -->
                                    <td class="py-4 px-4 align-top">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                                {{ Str::substr($post->author_name ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $post->author_name ?? 'Admin' }}
                                                </p>
                                                <p class="text-[10px] text-gray-400">
                                                    {{ $post->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 pl-4 pr-6 align-top text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.posts.show', $post) }}"
                                                class="p-2 rounded-lg text-gray-500 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-gray-100 dark:hover:bg-dark-700 transition"
                                                title="View Post Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <button type="button" onclick="confirmDeletePost({{ $post->id }}, '{{ addslashes($post->title) }}')"
                                                class="p-2 rounded-lg text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition"
                                                title="Delete Post">
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
            </div>

            <!-- VIEW 2: MODERN CARD GRID -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition">
                @foreach ($posts as $post)
                    <div class="flex flex-col rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition overflow-hidden group">
                        <!-- Hero Image / Header -->
                        <div class="relative aspect-video w-full bg-gray-100 dark:bg-dark-900 overflow-hidden">
                            @if ($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt=""
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80';"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-sky-500/20 to-indigo-500/20 text-sky-500 font-extrabold text-2xl">
                                    {{ Str::substr($post->title, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

                            <!-- Status badge overlay -->
                            <div class="absolute top-3 left-3">
                                @if ($post->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/90 text-white backdrop-blur-md shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-500/90 text-white backdrop-blur-md shadow-sm">
                                        Draft
                                    </span>
                                @endif
                            </div>

                            <!-- Reading time overlay -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-black/50 text-white backdrop-blur-md">
                                    {{ $post->reading_time ?? 1 }} min read
                                </span>
                            </div>

                            <!-- Category badge overlay -->
                            @if ($post->category_name)
                                <div class="absolute bottom-3 left-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/90 dark:bg-dark-800/90 text-gray-900 dark:text-white backdrop-blur-md">
                                        {{ $post->category_name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <a href="{{ route('admin.posts.show', $post) }}"
                                    class="block font-bold text-gray-900 dark:text-white hover:text-sky-500 dark:hover:text-sky-400 text-sm line-clamp-2 transition">
                                    {{ $post->title }}
                                </a>
                                @if ($post->sub_title)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ $post->sub_title }}
                                    </p>
                                @endif

                                <!-- Denormalized Tags Chips -->
                                @if (!empty($post->tags_cache) && is_array($post->tags_cache))
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach (array_slice($post->tags_cache, 0, 3) as $tag)
                                            <span class="text-[10px] px-2 py-0.5 rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 font-medium">
                                                #{{ $tag['name'] ?? $tag['slug'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Footer Metrics & Author -->
                            <div class="pt-3 border-t border-gray-100 dark:border-dark-700/60 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                        {{ Str::substr($post->author_name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-[11px] text-gray-600 dark:text-gray-300 font-medium truncate max-w-[100px]">
                                        {{ $post->author_name ?? 'Admin' }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400 text-[11px]">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ $post->views_count }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $post->likes_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <x-bladewind::modal name="delete-post-modal" type="error" title="Delete Post"
        ok_button_text="Delete Post" cancel_button_text="Cancel" ok_button_action="submitDeletePostModal()">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Are you sure you want to delete <strong id="delete-post-title" class="text-gray-900 dark:text-white font-semibold"></strong>?
            This will soft-delete the article while preserving historical database references.
        </p>
        <form id="delete-post-form" method="POST" action="" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-bladewind::modal>

    <script>
        function confirmDeletePost(id, title) {
            document.getElementById('delete-post-form').action = `/admin/posts/${id}`;
            const titleEl = document.getElementById('delete-post-title');
            if (titleEl) titleEl.innerText = title;
            showModal('delete-post-modal');
        }

        function submitDeletePostModal() {
            document.getElementById('delete-post-form').submit();
        }
    </script>
</x-app-layout>
