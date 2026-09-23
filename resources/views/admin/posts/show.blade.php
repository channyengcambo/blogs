<x-app-layout>
    <div class="p-6 sm:p-8 max-w-5xl mx-auto space-y-6" x-data="{
        copiedSlug: false,
        copiedJson: false,
        copySlug(slug) {
            navigator.clipboard.writeText(slug);
            this.copiedSlug = true;
            setTimeout(() => { this.copiedSlug = false; }, 2000);
        },
        copyJson(text) {
            navigator.clipboard.writeText(text);
            this.copiedJson = true;
            setTimeout(() => { this.copiedJson = false; }, 2000);
        }
    }">
        <!-- Top Navigation & Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.posts.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-sky-500 dark:text-gray-400 dark:hover:text-sky-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Posts</span>
            </a>

            <div class="flex items-center gap-2">
                <!-- Status Toggle Button -->
                <form method="POST" action="{{ route('admin.posts.toggle-status', $post) }}">
                    @csrf
                    @method('PATCH')
                    @if ($post->status === 'published')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-xs font-semibold transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Switch to Draft</span>
                        </button>
                    @else
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-xs font-semibold transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Publish Article</span>
                        </button>
                    @endif
                </form>

                <button type="button" onclick="confirmDeletePost({{ $post->id }}, '{{ addslashes($post->title) }}')"
                    class="p-2 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 border border-rose-500/20 text-xs font-semibold transition"
                    title="Delete Post">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Hero Article Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
            @if ($post->featured_image)
                <div class="relative h-60 sm:h-72 w-full overflow-hidden bg-dark-900">
                    <img src="{{ $post->featured_image }}" alt=""
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80';"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-900/80 via-transparent to-black/30"></div>
                    <div class="absolute top-4 left-4 flex items-center gap-2 z-10">
                        @if ($post->status === 'published')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500 text-white shadow-md">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500 text-white shadow-md">
                                Draft
                            </span>
                        @endif

                        @if ($post->category_name)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-sky-500 text-white shadow-md">
                                {{ $post->category_name }}
                            </span>
                        @endif
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-black/60 text-white backdrop-blur-md">
                            {{ $post->reading_time ?? 1 }} min read
                        </span>
                    </div>
                </div>
            @endif

            <div class="p-6 sm:p-8 space-y-5">
                <div class="space-y-3">
                    @if (!$post->featured_image)
                        <div class="flex items-center gap-2">
                            @if ($post->status === 'published')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    Draft
                                </span>
                            @endif

                            @if ($post->category_name)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                                    {{ $post->category_name }}
                                </span>
                            @endif

                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                &bull; {{ $post->reading_time ?? 1 }} min read
                            </span>
                        </div>
                    @endif

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-snug">
                        {{ $post->title }}
                    </h1>
                </div>

                @if ($post->sub_title)
                    <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                        {{ $post->sub_title }}
                    </p>
                @endif

                <!-- Metadata Row -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100 dark:border-dark-700 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold uppercase shadow-sm">
                            {{ Str::substr($post->author_name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-900 dark:text-white block">{{ $post->author_name ?? 'Admin User' }}</span>
                            <span>Created {{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Slug Pill -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 font-mono text-xs">
                        <span class="text-gray-400">slug:</span>
                        <span class="text-sky-600 dark:text-sky-400 font-semibold">{{ $post->slug }}</span>
                        <button type="button" @click="copySlug('{{ $post->slug }}')"
                            class="text-gray-400 hover:text-sky-500 transition" title="Copy slug">
                            <svg x-show="!copiedSlug" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span x-show="copiedSlug" class="text-emerald-500 font-sans text-[10px] font-bold" x-cloak>Copied!</span>
                        </button>
                    </div>
                </div>

                <!-- Cached Taxonomy Tags -->
                @if (!empty($post->tags_cache) && is_array($post->tags_cache))
                    <div class="flex flex-wrap items-center gap-2 pt-2">
                        <span class="text-xs text-gray-400 font-medium">Cached Tags:</span>
                        @foreach ($post->tags_cache as $tag)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                #{{ $tag['name'] ?? $tag['slug'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Engagement & Counters Strip (Future scaling counters) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <!-- Views -->
            <div class="p-4 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Views</p>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($post->views_count) }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Likes -->
            <div class="p-4 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Likes</p>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($post->likes_count) }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div class="p-4 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Comments</p>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($post->comments_count) }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Shares -->
            <div class="p-4 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Shares</p>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($post->shares_count) }}</h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Denormalized Database Snapshot (Zero Joins Architecture) -->
        <div class="p-6 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                        Denormalized Storage Snapshot (Fast Lookups)
                    </h3>
                </div>
                <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/20">
                    Zero SQL Joins Required
                </span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                The values below are stored directly on the <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-dark-900 font-mono text-[11px] text-sky-500">posts</code> table row. Feeds and public readers query only the posts table without joins on categories, users, or post_tags.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                <div class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80">
                    <p class="text-[10px] text-gray-400 uppercase font-mono">category_name</p>
                    <p class="font-bold text-gray-900 dark:text-white mt-1">{{ $post->category_name ?? 'null' }}</p>
                </div>
                <div class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80">
                    <p class="text-[10px] text-gray-400 uppercase font-mono">category_slug</p>
                    <p class="font-bold text-gray-900 dark:text-white mt-1">{{ $post->category_slug ?? 'null' }}</p>
                </div>
                <div class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80">
                    <p class="text-[10px] text-gray-400 uppercase font-mono">author_name</p>
                    <p class="font-bold text-gray-900 dark:text-white mt-1">{{ $post->author_name ?? 'null' }}</p>
                </div>
            </div>

            <!-- JSON Tags Cache Inspector -->
            <div class="p-3 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-100 dark:border-dark-700/80 font-mono text-xs">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] text-gray-400 uppercase">tags_cache (JSON column)</span>
                    <button type="button" @click="copyJson('{{ addslashes(json_encode($post->tags_cache, JSON_PRETTY_PRINT)) }}')"
                        class="text-[11px] text-sky-500 hover:text-sky-400 flex items-center gap-1 font-sans">
                        <span x-show="!copiedJson">Copy JSON</span>
                        <span x-show="copiedJson" x-cloak class="text-emerald-500 font-bold">Copied!</span>
                    </button>
                </div>
                <pre class="overflow-x-auto text-[11px] text-gray-700 dark:text-gray-300 leading-relaxed">{{ json_encode($post->tags_cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <!-- Article Content Preview -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-dark-800/90 border border-gray-200/80 dark:border-dark-700 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                Article Body Preview
            </h3>
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                {{ $post->body }}
            </div>
        </div>
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
