<x-app-layout>
    <div class="p-6 sm:p-8 max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb / Back button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-sky-500 dark:text-gray-400 dark:hover:text-sky-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Categories</span>
            </a>
        </div>

        <div class="bg-white dark:bg-dark-800/90 rounded-2xl shadow-sm border border-gray-200/80 dark:border-dark-700 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-gray-100 dark:border-dark-700">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Create New Category</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Add a taxonomy category to group related blog posts and assist readers in discovering content.
                </p>
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

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-bladewind::input
                        name="name"
                        id="name"
                        label="Category Name"
                        required="true"
                        error_message="A category name is required."
                        show_error_inline="true"
                        selectedValue="{{ old('name', '') }}"
                        placeholder="e.g. Artificial Intelligence, Cloud & DevOps, UI/UX Design"
                        onkeyup="updateLiveSlug(this.value)" />
                </div>

                <!-- Live URL Preview -->
                <div class="px-4 py-2.5 rounded-xl bg-gray-50 dark:bg-dark-900 border border-gray-200/80 dark:border-dark-700 text-xs">
                    <span class="text-gray-400">Public URL:</span>
                    <span class="font-mono text-sky-500 font-medium ml-1">
                        yourblog.com/categories/<span id="live-slug-text" class="underline decoration-dotted font-bold">new-category</span>
                    </span>
                </div>

                <div>
                    <x-bladewind::textarea
                        name="description"
                        id="description"
                        label="Description (Optional)"
                        selectedValue="{{ old('description', '') }}"
                        placeholder="Describe what kind of articles and tutorials fall under this category..."
                        rows="4"
                        onkeyup="updateDescCounter(this.value)" />
                    <div class="text-[11px] text-gray-400 text-right mt-1">
                        <span id="desc-char-counter">0</span> / 1000 characters
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100 dark:border-dark-700">
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 dark:border-dark-600 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-dark-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white text-xs font-semibold shadow-md shadow-sky-500/25 transition">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                liveEl.innerText = slug || 'new-category';
            }
        }

        function updateDescCounter(val) {
            const countEl = document.getElementById('desc-char-counter');
            if (countEl) {
                countEl.innerText = val ? val.length : 0;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateLiveSlug(@json(old('name', '')));
            updateDescCounter(@json(old('description', '')));
        });
    </script>
</x-app-layout>
