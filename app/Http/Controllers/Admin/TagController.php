<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');

        // Calculate KPI Metrics for the header strip
        $totalTags = Tag::count();
        $totalPosts = \App\Models\Post::count();
        $topTag = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->first();
        $emptyTagsCount = Tag::doesntHave('posts')->count();

        $query = Tag::query()->withCount('posts');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filter === 'with_posts') {
            $query->has('posts');
        } elseif ($filter === 'empty') {
            $query->doesntHave('posts');
        }

        $tags = $query->latest()
            ->paginate(12)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => TagResource::collection($tags),
                'meta' => [
                    'current_page' => $tags->currentPage(),
                    'last_page' => $tags->lastPage(),
                    'total' => $tags->total(),
                    'per_page' => $tags->perPage(),
                ],
            ]);
        }

        $tagsTableData = $tags->map(function (Tag $tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'description' => $tag->description ? Str::limit($tag->description, 70) : '-',
                'posts' => $tag->posts_count,
                'created_at' => $tag->created_at?->format('M d, Y'),
                'raw_description' => $tag->description ?? '',
            ];
        })->toArray();

        return view('admin.tags.index', [
            'tags' => $tags,
            'tagsTableData' => $tagsTableData,
            'search' => $search,
            'filter' => $filter,
            'totalTags' => $totalTags,
            'totalPosts' => $totalPosts,
            'topTag' => $topTag,
            'emptyTagsCount' => $emptyTagsCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request): RedirectResponse|JsonResponse
    {
        $tag = Tag::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tag created successfully.',
                'tag' => new TagResource($tag),
            ], 201);
        }

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Tag $tag): View|JsonResponse
    {
        $tag->loadCount('posts');
        $tag->load(['posts' => function ($query) {
            $query->latest('posts.created_at')->limit(10);
        }]);

        if ($request->wantsJson()) {
            return response()->json([
                'tag' => new TagResource($tag),
            ]);
        }

        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Tag $tag): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'tag' => new TagResource($tag),
            ]);
        }

        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag): RedirectResponse|JsonResponse
    {
        $tag->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tag updated successfully.',
                'tag' => new TagResource($tag),
            ]);
        }

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Tag $tag): RedirectResponse|JsonResponse
    {
        $tag->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tag deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
