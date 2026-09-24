<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display the public blog homepage with hero, featured post, categories, and paginated feed.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $tagSlug = $request->query('tag');
        $sort = $request->query('sort', 'latest');

        $query = Post::published()
            ->with(['category:id,name,slug,description']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sub_title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // Category filter
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = Category::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        // Tag filter
        $activeTag = null;
        if ($tagSlug) {
            $activeTag = Tag::where('slug', $tagSlug)->first();
            if ($activeTag) {
                $query->whereHas('tags', function ($q) use ($activeTag) {
                    $q->where('tags.id', $activeTag->id);
                });
            }
        }

        // Sorting
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('views_count')->orderByDesc('published_at');
                break;
            case 'trending':
                $query->orderByDesc('likes_count')->orderByDesc('published_at');
                break;
            case 'oldest':
                $query->orderBy('published_at');
                break;
            case 'latest':
            default:
                $query->orderByDesc('published_at');
                break;
        }

        // Retrieve featured post for hero section (only on unfiltered page 1)
        $isFiltered = !empty($search) || !empty($categorySlug) || !empty($tagSlug) || (int) $request->query('page', 1) > 1;
        $featuredPost = null;

        if (!$isFiltered) {
            $featuredPost = Post::published()
                ->with('category:id,name,slug')
                ->orderByDesc('views_count')
                ->first();
        }

        // Paginated post feed
        $posts = $query->paginate(6)->withQueryString();

        // Trending posts for sidebar/ticker
        $trendingPosts = Post::published()
            ->with('category:id,name,slug')
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        // Category topics with published post counts
        $categories = Category::whereHas('posts', function ($q) {
            $q->published();
        })
            ->withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->orderByDesc('posts_count')
            ->get(['id', 'name', 'slug', 'description']);

        // Popular tags
        $tags = Tag::whereHas('posts', function ($q) {
            $q->published();
        })
            ->withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->orderByDesc('posts_count')
            ->take(12)
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Frontend/Home', [
            'posts' => $posts,
            'featuredPost' => $featuredPost,
            'trendingPosts' => $trendingPosts,
            'categories' => $categories,
            'tags' => $tags,
            'filters' => [
                'search' => $search ?? '',
                'category' => $categorySlug ?? '',
                'tag' => $tagSlug ?? '',
                'sort' => $sort,
            ],
            'activeCategory' => $activeCategory,
            'activeTag' => $activeTag,
        ]);
    }

    /**
     * Display a single public blog article with reading time, hero image, and related articles.
     */
    public function show(string $slug): Response
    {
        $post = Post::published()
            ->with(['category:id,name,slug,description', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count on read
        $post->increment('views_count');

        // Fetch related posts from same category or latest
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, function ($q) use ($post) {
                $q->where('category_id', $post->category_id);
            })
            ->with('category:id,name,slug')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // If fewer than 3 related by category, backfill with top posts
        if ($relatedPosts->count() < 3) {
            $backfill = Post::published()
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->with('category:id,name,slug')
                ->orderByDesc('views_count')
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($backfill);
        }

        return Inertia::render('Frontend/PostDetail', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Increment like count for a post.
     */
    public function like(Post $post): JsonResponse|RedirectResponse
    {
        if (!$post->isPublished()) {
            abort(404);
        }

        $post->increment('likes_count');

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes_count' => $post->fresh()->likes_count,
            ]);
        }

        return back()->with('success', 'Thanks for liking this article!');
    }

    /**
     * Newsletter subscription endpoint.
     */
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing! Check your inbox soon.',
            ]);
        }

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
