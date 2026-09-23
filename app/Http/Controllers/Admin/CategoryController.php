<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');

        // Calculate KPI Metrics for the header strip
        $totalCategories = Category::count();
        $totalPosts = \App\Models\Post::count();
        $topCategory = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->first();
        $emptyCategoriesCount = Category::doesntHave('posts')->count();

        $query = Category::query()->withCount('posts');

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

        $perPage = in_array((int) $request->query('per_page'), [6, 12, 24, 48]) ? (int) $request->query('per_page') : 6;
        $categories = $query->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => CategoryResource::collection($categories),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                ],
            ]);
        }

        $categoriesTableData = $categories->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description ? Str::limit($category->description, 70) : '-',
                'posts' => $category->posts_count,
                'created_at' => $category->created_at?->format('M d, Y'),
                'raw_description' => $category->description ?? '',
            ];
        })->toArray();

        return view('admin.categories.index', [
            'categories' => $categories,
            'categoriesTableData' => $categoriesTableData,
            'search' => $search,
            'filter' => $filter,
            'totalCategories' => $totalCategories,
            'totalPosts' => $totalPosts,
            'topCategory' => $topCategory,
            'emptyCategoriesCount' => $emptyCategoriesCount,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): RedirectResponse|JsonResponse
    {
        $category = Category::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Category created successfully.',
                'category' => new CategoryResource($category),
            ], 201);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category): View|JsonResponse
    {
        $category->loadCount('posts');
        $category->load(['posts' => function ($query) {
            $query->latest()->limit(10);
        }]);

        if ($request->wantsJson()) {
            return response()->json([
                'category' => new CategoryResource($category),
            ]);
        }

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Category $category): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'category' => new CategoryResource($category),
            ]);
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse|JsonResponse
    {
        $category->update($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Category updated successfully.',
                'category' => new CategoryResource($category),
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        $category->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Category deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
