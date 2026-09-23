<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of posts with KPI metrics and filters.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $categoryId = $request->query('category_id');
        $sort = $request->query('sort', 'latest');

        // KPI Metrics for high-level management overview
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalViews = (int) Post::sum('views_count');

        // Query with Zero-Join advantage (using denormalized category & author info)
        $query = Post::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sub_title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['published', 'draft', 'archived'])) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($sort === 'views') {
            $query->orderByDesc('views_count');
        } elseif ($sort === 'title') {
            $query->orderBy('title');
        } elseif ($sort === 'oldest') {
            $query->oldest('posts.created_at');
        } else {
            $query->latest('posts.created_at');
        }

        $posts = $query->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => PostResource::collection($posts),
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'total' => $posts->total(),
                    'per_page' => $posts->perPage(),
                ],
                'kpi' => [
                    'total_posts' => $totalPosts,
                    'published_posts' => $publishedPosts,
                    'draft_posts' => $draftPosts,
                    'total_views' => $totalViews,
                ],
            ]);
        }

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('admin.posts.index', [
            'posts' => $posts,
            'categories' => $categories,
            'totalPosts' => $totalPosts,
            'publishedPosts' => $publishedPosts,
            'draftPosts' => $draftPosts,
            'totalViews' => $totalViews,
            'search' => $search,
            'status' => $status,
            'categoryId' => $categoryId,
            'sort' => $sort,
        ]);
    }

    /**
     * Display the specified post with denormalized & relational context.
     */
    public function show(Request $request, Post $post): View|JsonResponse
    {
        $post->load(['category', 'tags', 'user']);

        if ($request->wantsJson()) {
            return response()->json([
                'post' => new PostResource($post),
            ]);
        }

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Quick 1-click status toggle between published and draft.
     */
    public function toggleStatus(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft']);
            $message = "Post '{$post->title}' changed to draft.";
        } else {
            $post->update([
                'status' => 'published',
                'published_at' => $post->published_at ?? now(),
            ]);
            $message = "Post '{$post->title}' is now published.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'post' => new PostResource($post->fresh()),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified post from storage (soft delete).
     */
    public function destroy(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $title = $post->title;
        $post->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Post '{$title}' has been deleted successfully.",
            ]);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Post '{$title}' was deleted successfully.");
    }
}
