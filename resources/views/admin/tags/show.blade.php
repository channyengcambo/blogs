<x-app-layout>
    <div class="p-6 sm:p-8 max-w-5xl mx-auto space-y-6" x-data="{
        copiedSlug: false,
        copySlug(slug) {
            navigator.clipboard.writeText(slug);
            this.copiedSlug = true;
            setTimeout(() => { this.copiedSlug = false; }, 2000);
        }
    }">
        <!-- Navigation & Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tags.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Tags</span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.tags.edit', $tag) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white dark:bg-dark-800 border border-gray-200/80 dark:border-dark-700 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-dark-700 shadow-sm transition">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit Tag</span>
                </a>
            </div>
        </div>

        <!-- Hero Overview Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-gray-100 dark:border-dark-700">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 border border-indigo-500/30 text-indigo-500 flex items-center justify-center font-black text-2xl flex-shrink-0 shadow-sm">
                        #
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ $tag->name }}
                            </h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                Active Tag
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <!-- Slug Pill with Copy -->
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 font-mono text-xs text-gray-600 dark:text-gray-300">
                                <span class="text-gray-400">/</span>
                                <span class="font-medium">{{ $tag->slug }}</span>
                                <button type="button" @click="copySlug('{{ $tag->slug }}')"
                                        class="text-gray-400 hover:text-indigo-500 ml-1 transition" title="Copy slug">
                                    <svg x-show="!copiedSlug" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span x-show="copiedSlug" class="text-emerald-500 font-sans text-[10px] font-bold" style="display: none;">Copied!</span>
                                </button>
                            </div>
                            <span class="text-gray-300 dark:text-gray-700">&bull;</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Created {{ $tag->created_at?->format('F d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tagged Posts Metric Widget -->
                <div class="flex items-center gap-4 bg-gray-50/80 dark:bg-dark-900/60 p-4 rounded-2xl border border-gray-200/60 dark:border-dark-700/60 self-start sm:self-center">
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500">
                            {{ $tag->posts_count ?? $tag->posts()->count() }}
                        </div>
                        <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5">
                            Tagged Posts
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="pt-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
                    Description & Overview
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-w-3xl">
                    {{ $tag->description ?: 'No description provided for this tag.' }}
                </p>
            </div>
        </div>

        <!-- Frontend API Integration Preview Card -->
        <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-purple-950 border border-purple-900/40 p-6 text-white shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        GET
                    </span>
                    <span class="text-xs font-mono text-gray-300">
                        /api/tags/{{ $tag->slug }}
                    </span>
                </div>
                <span class="text-[11px] text-purple-300 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Frontend Headless Ready
                </span>
            </div>
            <p class="text-xs text-gray-400 leading-relaxed">
                When you build your frontend, this tag and its associated articles can be fetched in JSON format by sending an <code class="text-indigo-300">Accept: application/json</code> header to this endpoint.
            </p>
        </div>

        <!-- Associated Posts List -->
        <div class="rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-dark-700">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tagged Blog Posts</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Articles labeled with this keyword</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-dark-700 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    {{ $tag->posts->count() }} Posts
                </span>
            </div>

            @if($tag->posts->isEmpty())
                <div class="py-12 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gray-100 dark:bg-dark-700 text-gray-400 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">No posts tagged yet</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Blog posts tagged with this keyword will appear here automatically.
                    </p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-dark-700/60">
                    @foreach($tag->posts as $post)
                        <div class="py-3.5 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-dark-700/30 px-3 rounded-xl transition">
                            <div class="min-w-0 pr-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $post->title }}</h4>
                                <div class="flex items-center gap-2 mt-1 text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    <span>/{{ $post->slug }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $post->created_at?->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
