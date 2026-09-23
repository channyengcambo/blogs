<x-app-layout>
    <!-- ApexCharts CDN for Ultra-Sleek Modern SaaS Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="px-6 py-6 space-y-6 w-full">
        <!-- Top Executive Header Banner -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Executive Dashboard
                    </h1>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Telemetry
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-2xl">
                    Welcome back, {{ auth()->user()->name }}. Performance telemetry, readership engagement, and database
                    architecture status.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <span
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-dark-800 border border-gray-200/80 dark:border-dark-700 text-xs font-medium text-gray-600 dark:text-gray-300 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ now()->format('l, M j, Y') }}</span>
                </span>
                <a href="{{ route('admin.posts.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-sky-500 via-indigo-500 to-purple-500 hover:from-sky-400 hover:to-purple-400 text-white text-xs font-semibold shadow-lg shadow-sky-500/20 hover:shadow-sky-500/30 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>View Posts Table</span>
                </a>
            </div>
        </div>

        <!-- 5 KPI Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Card 1: Total Articles -->
            <div
                class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total
                            Articles</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPosts }}</h3>
                        <p
                            class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1">
                            <span>{{ $publishedPosts }} live</span>
                            <span class="text-gray-300 dark:text-gray-600">&bull;</span>
                            <span class="text-amber-500">{{ $draftPosts }} drafts</span>
                        </p>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center border border-sky-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Views -->
            <div
                class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reader
                            Views</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($totalViews) }}
                        </h3>
                        <p class="text-[11px] text-sky-600 dark:text-sky-400 font-medium mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span>Total impressions</span>
                        </p>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center border border-indigo-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Engagements -->
            <div
                class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Engagements</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($totalEngagements) }}
                        </h3>
                        <p class="text-[11px] text-pink-600 dark:text-pink-400 font-medium mt-1">
                            {{ $totalLikes }} likes &bull; {{ $totalComments }} comments
                        </p>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center border border-pink-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 4: Categories -->
            <div
                class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Categories</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalCategories }}</h3>
                        <a href="{{ route('admin.categories.index') }}"
                            class="text-[11px] text-amber-600 dark:text-amber-400 font-medium mt-1 hover:underline block">
                            Manage topics &rarr;
                        </a>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 5: Taxonomy Tags -->
            <div
                class="relative overflow-hidden p-5 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Taxonomy Tags</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalTags }}</h3>
                        <a href="{{ route('admin.tags.index') }}"
                            class="text-[11px] text-purple-600 dark:text-purple-400 font-medium mt-1 hover:underline block">
                            Manage tags &rarr;
                        </a>
                    </div>
                    <div
                        class="w-11 h-11 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center border border-purple-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Analytics Section (2-Column Chart Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Spline Area Chart: Readership & Engagement (2 Columns) -->
            <div
                class="lg:col-span-2 p-6 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                Readership & Engagement Trajectory
                            </h2>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Rolling 14-day telemetry showing article impressions and interactions
                        </p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-sky-500"></span>
                            Views
                        </span>
                        <span class="flex items-center gap-1.5 font-medium text-gray-700 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                            Engagements
                        </span>
                    </div>
                </div>

                <!-- ApexChart Container -->
                <div class="relative min-h-[320px] w-full">
                    <div id="views-engagement-chart" class="w-full"></div>
                </div>
            </div>

            <!-- Donut Chart: Category Content Share (1 Column) -->
            <div
                class="p-6 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">
                            Content by Category
                        </h2>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Distribution of published articles across topics
                    </p>
                </div>

                <!-- ApexChart Container -->
                <div class="relative flex items-center justify-center min-h-[260px] py-2">
                    <div id="category-distribution-chart" class="w-full"></div>
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-dark-700 text-center">
                    <span class="text-[11px] text-gray-400">
                        {{ $totalCategories }} categories indexed in database
                    </span>
                </div>
            </div>
        </div>

        <!-- Bottom Grid: Top Performing Articles & Architecture Status -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Posts Leaderboard (2 Columns) -->
            <div
                class="lg:col-span-2 overflow-hidden rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-dark-700 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                Top Performing Articles
                            </h2>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Ranked by total reader views and community engagement
                        </p>
                    </div>
                    <a href="{{ route('admin.posts.index') }}"
                        class="text-xs font-semibold text-sky-500 hover:text-sky-400 flex items-center gap-1 transition">
                        <span>See all posts</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto lg:overflow-x-visible">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr
                                class="border-b border-gray-100 dark:border-dark-700 bg-gray-50/75 dark:bg-dark-900/60 text-gray-400 font-semibold uppercase tracking-wider text-[10px]">
                                <th class="py-3 pl-6 pr-2 w-14">Rank</th>
                                <th class="py-3 px-3">Article</th>
                                <th class="py-3 px-3 w-32">Category</th>
                                <th class="py-3 px-3 w-20">Views</th>
                                <th class="py-3 px-3 w-28">Engagements</th>
                                <th class="py-3 pl-2 pr-6 w-20 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-dark-700/60 text-gray-700 dark:text-gray-300">
                            @forelse ($topPosts as $index => $post)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-dark-700/40 transition-colors">
                                    <td class="py-3.5 pl-6 pr-2 font-bold text-gray-400">
                                        @if ($index === 0)
                                            <span
                                                class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-500 border border-amber-500/30 flex items-center justify-center text-xs font-black">
                                                1
                                            </span>
                                        @elseif ($index === 1)
                                            <span
                                                class="w-6 h-6 rounded-full bg-slate-300/20 text-slate-300 border border-slate-300/30 flex items-center justify-center text-xs font-black">
                                                2
                                            </span>
                                        @elseif ($index === 2)
                                            <span
                                                class="w-6 h-6 rounded-full bg-amber-700/20 text-amber-600 border border-amber-700/30 flex items-center justify-center text-xs font-black">
                                                3
                                            </span>
                                        @else
                                            <span class="text-xs px-2 text-gray-400 font-medium">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            @if ($post->featured_image)
                                                <img src="{{ $post->featured_image }}" alt=""
                                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=200&q=80';"
                                                    class="w-9 h-9 rounded-lg object-cover border border-gray-200/80 dark:border-dark-700 flex-shrink-0 shadow-sm">
                                            @else
                                                <div
                                                    class="w-9 h-9 rounded-lg bg-sky-500/10 text-sky-500 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                    {{ Str::substr($post->title, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0 flex-1">
                                                <a href="{{ route('admin.posts.show', $post) }}"
                                                    class="font-semibold text-gray-900 dark:text-white hover:text-sky-500 dark:hover:text-sky-400 transition truncate block">
                                                    {{ $post->title }}
                                                </a>
                                                <p class="text-[10px] text-gray-400 truncate">
                                                    {{ $post->reading_time ?? 1 }} min read &bull;
                                                    {{ $post->published_at?->format('M d, Y') ?? 'Draft' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 truncate max-w-[110px]">
                                            {{ $post->category_name ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($post->views_count) }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-0.5 text-[11px] text-pink-500">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $post->likes_count }}
                                            </span>
                                            <span class="flex items-center gap-0.5 text-[11px] text-indigo-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                {{ $post->comments_count }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 pl-2 pr-6 text-right">
                                        <a href="{{ route('admin.posts.show', $post) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-medium bg-gray-100 dark:bg-dark-700 text-gray-700 dark:text-gray-300 hover:text-sky-500 dark:hover:text-sky-400 transition">
                                            <span>View</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400 text-xs">
                                        No articles found. Seed or create your first blog post.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Architecture & Quick Actions Sidebar (1 Column) -->
            <div class="space-y-6">
                <!-- Zero-Join Fast Lookup Status Card -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                Architecture Health
                            </h3>
                        </div>
                        <span
                            class="text-[10px] font-semibold text-emerald-500 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">
                            Zero Joins
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Denormalized columns (<code class="text-sky-400 font-mono text-[10px]">category_name</code>,
                        <code class="text-sky-400 font-mono text-[10px]">tags_cache</code>) allow instant reading
                        throughput without SQL relational bottleneck.
                    </p>

                    <div class="grid grid-cols-2 gap-3 pt-1 text-xs">
                        <div
                            class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80">
                            <p class="text-[10px] text-gray-400 uppercase font-mono">Query Joins</p>
                            <p class="font-bold text-emerald-500 mt-0.5">0 Joins</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80">
                            <p class="text-[10px] text-gray-400 uppercase font-mono">Tags Caching</p>
                            <p class="font-bold text-sky-400 mt-0.5">JSON Cache</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Hub -->
                <div
                    class="p-6 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.posts.index') }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-dark-900 hover:bg-sky-500/10 border border-gray-100 dark:border-dark-700 hover:border-sky-500/30 text-xs font-semibold text-gray-800 dark:text-gray-200 hover:text-sky-500 dark:hover:text-sky-400 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span>Browse Posts Table</span>
                            </span>
                            <span class="text-gray-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-dark-900 hover:bg-amber-500/10 border border-gray-100 dark:border-dark-700 hover:border-amber-500/30 text-xs font-semibold text-gray-800 dark:text-gray-200 hover:text-amber-500 dark:hover:text-amber-400 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span>Manage Categories</span>
                            </span>
                            <span class="text-gray-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>

                        <a href="{{ route('admin.tags.index') }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-dark-900 hover:bg-purple-500/10 border border-gray-100 dark:border-dark-700 hover:border-purple-500/30 text-xs font-semibold text-gray-800 dark:text-gray-200 hover:text-purple-500 dark:hover:text-purple-400 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span>Manage Taxonomy Tags</span>
                            </span>
                            <span class="text-gray-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to render ApexCharts with dark theme & sleek gradients -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Readership & Engagement Area Chart
            const timelineCategories = @json($trendDates);
            const viewsSeries = @json($viewsTrend);
            const engagementSeries = @json($engagementTrend);

            const areaOptions = {
                series: [
                    {
                        name: 'Reader Views',
                        data: viewsSeries
                    },
                    {
                        name: 'Engagements',
                        data: engagementSeries
                    }
                ],
                chart: {
                    type: 'area',
                    height: 320,
                    fontFamily: 'Figtree, sans-serif',
                    toolbar: { show: false },
                    background: 'transparent',
                    zoom: { enabled: false }
                },
                colors: ['#38bdf8', '#c084fc'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 95, 100]
                    }
                },
                grid: {
                    borderColor: '#262a2f',
                    strokeDashArray: 4,
                    padding: { left: 10, right: 10, top: 0, bottom: 0 }
                },
                xaxis: {
                    categories: timelineCategories,
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '11px',
                            fontFamily: 'Figtree, sans-serif'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontSize: '11px',
                            fontFamily: 'Figtree, sans-serif'
                        },
                        formatter: function (val) {
                            return val >= 1000 ? (val / 1000).toFixed(1) + 'k' : val;
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    x: { show: true },
                    y: {
                        formatter: function (val) {
                            return val.toLocaleString();
                        }
                    }
                },
                legend: { show: false }
            };

            const areaChart = new ApexCharts(document.querySelector("#views-engagement-chart"), areaOptions);
            areaChart.render();

            // Chart 2: Category Distribution Donut Chart
            const categoryLabels = @json($categoryLabels);
            const categorySeries = @json($categoryCounts);

            const donutOptions = {
                series: categorySeries.length > 0 ? categorySeries : [1],
                labels: categoryLabels.length > 0 ? categoryLabels : ['Uncategorized'],
                chart: {
                    type: 'donut',
                    height: 260,
                    fontFamily: 'Figtree, sans-serif',
                    background: 'transparent'
                },
                colors: ['#0ea5e9', '#6366f1', '#a855f7', '#ec4899', '#f59e0b', '#10b981', '#14b8a6', '#f43f5e'],
                stroke: {
                    width: 2,
                    colors: ['#1C1F24']
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    color: '#9ca3af'
                                },
                                value: {
                                    show: true,
                                    fontSize: '20px',
                                    fontWeight: 'bold',
                                    color: '#ffffff',
                                    formatter: function (val) {
                                        return val;
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Articles',
                                    color: '#9ca3af',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    fontFamily: 'Figtree, sans-serif',
                    labels: { colors: '#9ca3af' },
                    markers: { radius: 4 }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return val + ' Articles';
                        }
                    }
                }
            };

            const donutChart = new ApexCharts(document.querySelector("#category-distribution-chart"), donutOptions);
            donutChart.render();
        });
    </script>
</x-app-layout>