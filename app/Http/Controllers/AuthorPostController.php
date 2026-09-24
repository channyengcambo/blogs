<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AuthorPostController extends Controller
{
    /**
     * Display the authenticated author's dashboard with their articles and metrics.
     */
    public function dashboard(Request $request): Response
    {
        $user = Auth::user();
        $status = $request->query('status');
        $search = $request->query('search');

        // Query only current author's posts
        $query = Post::where('user_id', $user->id)
            ->with(['category:id,name,slug']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sub_title', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['published', 'draft'])) {
            $query->where('status', $status);
        }

        $posts = $query->latest()->paginate(8)->withQueryString();

        // Calculate KPI metrics for this author
        $totalPosts = Post::where('user_id', $user->id)->count();
        $publishedPosts = Post::where('user_id', $user->id)->where('status', 'published')->count();
        $draftPosts = Post::where('user_id', $user->id)->where('status', 'draft')->count();
        $totalViews = (int) Post::where('user_id', $user->id)->sum('views_count');
        $totalLikes = (int) Post::where('user_id', $user->id)->sum('likes_count');

        return Inertia::render('Author/Dashboard', [
            'posts' => $posts,
            'metrics' => [
                'total_posts' => $totalPosts,
                'published_posts' => $publishedPosts,
                'draft_posts' => $draftPosts,
                'total_views' => $totalViews,
                'total_likes' => $totalLikes,
            ],
            'filters' => [
                'status' => $status ?? '',
                'search' => $search ?? '',
            ],
        ]);
    }

    /**
     * Show form to write a new article.
     */
    public function create(): Response
    {
        Gate::authorize('create', Post::class);

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);
        $tags = Tag::orderBy('name')->get(['id', 'name', 'slug']);

        return Inertia::render('Author/PostEditor', [
            'post' => null,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'body' => 'required|string',
            'featured_image' => 'nullable|url|max:2048',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $user = Auth::user();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'sub_title' => $validated['sub_title'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'body' => $validated['body'],
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        if (! empty($validated['tags'])) {
            $post->syncTagsWithCache($validated['tags']);
        }

        $message = $post->status === 'published'
            ? 'Article published successfully and is now live!'
            : 'Draft saved successfully.';

        return redirect()->route('author.dashboard')->with('success', $message);
    }

    /**
     * Show the form for editing an existing article.
     */
    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post);

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);
        $tags = Tag::orderBy('name')->get(['id', 'name', 'slug']);

        // Load current post tag IDs
        $postTagIds = $post->tags()->pluck('tags.id')->toArray();

        return Inertia::render('Author/PostEditor', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'sub_title' => $post->sub_title ?? '',
                'category_id' => $post->category_id,
                'body' => $post->body,
                'featured_image' => $post->featured_image ?? '',
                'status' => $post->status,
                'slug' => $post->slug,
                'tag_ids' => $postTagIds,
            ],
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'body' => 'required|string',
            'featured_image' => 'nullable|url|max:2048',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $wasDraft = $post->status === 'draft';

        $post->update([
            'title' => $validated['title'],
            'sub_title' => $validated['sub_title'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'body' => $validated['body'],
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'published_at' => ($wasDraft && $validated['status'] === 'published') ? now() : $post->published_at,
        ]);

        $post->syncTagsWithCache($validated['tags'] ?? []);

        return redirect()->route('author.dashboard')->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('author.dashboard')->with('success', 'Article deleted.');
    }
}
