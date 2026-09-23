<x-app-layout>
    <div class="p-6 sm:p-8 max-w-4xl mx-auto space-y-6">
        <!-- Back button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.tags.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Tags</span>
            </a>
            <a href="{{ route('admin.tags.show', $tag) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-500 hover:text-indigo-600 transition">
                <span>View Details Page</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="bg-white dark:bg-dark-800/90 rounded-2xl shadow-sm border border-gray-200/80 dark:border-dark-700 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-gray-100 dark:border-dark-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Edit Tag: {{ $tag->name }}</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Modify tag keyword and description.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-dark-900 border border-gray-200 dark:border-dark-700 font-mono text-xs text-gray-600 dark:text-gray-300">
                        /{{ $tag->slug }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-500 border border-indigo-500/20">
                        {{ $tag->posts_count ?? $tag->posts()->count() }} {{ Str::plural('post', $tag->posts_count ?? $tag->posts()->count()) }}
                    </span>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs">
                    <p class="font-semibold">Please correct the errors below:</p>
                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-bladewind::input
                        name="name"
                        id="tag_name"
                        label="Tag Name"
                        required="true"
                        error_message="A tag name is required."
                        show_error_inline="true"
                        selectedValue="{{ old('name', $tag->name) }}"
                        placeholder="e.g. Laravel, React, Tailwind, Docker"
                        onkeyup="updateLiveSlug(this.value)" />
                </div>

                <!-- Live URL Preview -->
                <div class="px-4 py-2.5 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-xs">
                    <span class="text-gray-400">Public URL Preview:</span>
                    <span class="font-mono text-indigo-500 font-medium ml-1">
                        yourblog.com/tags/<span id="live-slug-text" class="underline decoration-dotted font-bold">{{ $tag->slug }}</span>
                    </span>
                </div>

                <div>
                    <x-bladewind::textarea
                        name="description"
                        id="tag_description"
                        label="Description (Optional)"
                        selectedValue="{{ old('description', $tag->description) }}"
                        placeholder="Describe what kind of articles use this tag..."
                        rows="4"
                        onkeyup="updateDescCounter(this.value)" />
                    <div class="text-[11px] text-gray-400 text-right mt-1">
                        <span id="desc-char-counter">0</span> / 1000 characters
                    </div>
                </div>

                <div class="flex items-center justify-between pt-5 border-t border-gray-100 dark:border-dark-700">
                    <button type="button" onclick="showModal('delete-tag-modal')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-rose-200 dark:border-rose-900/30 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Delete Tag</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.tags.index') }}"
                            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-dark-600 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white text-xs font-semibold shadow-md shadow-indigo-500/25 transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modern Delete Confirmation Modal --}}
    <x-bladewind::modal
        type="error"
        name="delete-tag-modal"
        title="Delete Tag"
        ok_button_label="Confirm Delete"
        ok_button_action="domEl('#delete-tag-form').submit()"
        cancel_button_label="Cancel"
        size="small">

        <div class="text-left py-2 space-y-2">
            <p class="text-sm text-gray-800 dark:text-gray-200">
                Are you sure you want to permanently delete tag <strong class="text-rose-500 font-bold">{{ $tag->name }}</strong>?
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Posts currently tagged with this term will remain intact, and this tag will simply be unlinked.
            </p>
        </div>

        <form id="delete-tag-form" method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-bladewind::modal>

    <script>
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
                liveEl.innerText = slug || @json($tag->slug);
            }
        }

        function updateDescCounter(val) {
            const countEl = document.getElementById('desc-char-counter');
            if (countEl) {
                countEl.innerText = val ? val.length : 0;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateDescCounter(@json(old('description', $tag->description ?? '')));
        });
    </script>
</x-app-layout>
