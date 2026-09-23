<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'author_name',
        'category_id',
        'category_name',
        'category_slug',
        'title',
        'sub_title',
        'slug',
        'body',
        'featured_image',
        'tags_cache',
        'status',
        'published_at',
        'reading_time',
        'views_count',
        'likes_count',
        'comments_count',
        'shares_count',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'tags_cache' => 'array',
            'published_at' => 'datetime',
            'reading_time' => 'integer',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'comments_count' => 'integer',
            'shares_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            // Auto generate unique slug if not provided
            if (empty($post->slug)) {
                $post->slug = app(SlugService::class)->generate($post->title, static::class);
            }

            // Denormalize author information
            if (empty($post->user_id) && Auth::check()) {
                $post->user_id = Auth::id();
            }
            if (empty($post->author_name)) {
                if ($post->user_id) {
                    $user = User::find($post->user_id);
                    $post->author_name = $user?->name ?? 'Admin';
                } elseif (Auth::check()) {
                    $post->author_name = Auth::user()->name;
                }
            }

            // Denormalize category information for zero-join queries
            if ($post->category_id && (empty($post->category_name) || empty($post->category_slug))) {
                $category = Category::find($post->category_id);
                if ($category) {
                    $post->category_name = $category->name;
                    $post->category_slug = $category->slug;
                }
            }

            // Calculate reading time (~200 words per minute)
            if (!empty($post->body) && empty($post->reading_time)) {
                $wordCount = str_word_count(strip_tags($post->body));
                $post->reading_time = max(1, (int) ceil($wordCount / 200));
            }

            // Publishing timestamp
            if ($post->status === 'published' && empty($post->published_at)) {
                $post->published_at = now();
            }

            if (Auth::check()) {
                $post->created_by ??= Auth::id();
            }
        });

        static::updating(function (Post $post) {
            if ($post->isDirty('title')) {
                $post->slug = app(SlugService::class)->generate($post->title, static::class, $post->id);
            }

            if ($post->isDirty('category_id')) {
                if ($post->category_id) {
                    $category = Category::find($post->category_id);
                    $post->category_name = $category?->name;
                    $post->category_slug = $category?->slug;
                } else {
                    $post->category_name = null;
                    $post->category_slug = null;
                }
            }

            if ($post->isDirty('body')) {
                $wordCount = str_word_count(strip_tags($post->body));
                $post->reading_time = max(1, (int) ceil($wordCount / 200));
            }

            if ($post->isDirty('status') && $post->status === 'published' && empty($post->published_at)) {
                $post->published_at = now();
            }

            if (Auth::check()) {
                $post->updated_by = Auth::id();
            }
        });

        static::deleting(function (Post $post) {
            if (Auth::check() && !$post->isForceDeleting()) {
                $post->deleted_by = Auth::id();
                $post->saveQuietly();
            }
        });
    }

    /**
     * Sync post tags and update the denormalized tags_cache JSON column.
     *
     * @param  array<int>  $tagIds
     */
    public function syncTagsWithCache(array $tagIds): void
    {
        $this->tags()->sync($tagIds);

        $tags = Tag::whereIn('id', $tagIds)->get(['id', 'name', 'slug']);
        $this->tags_cache = $tags->map(fn (Tag $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'slug' => $t->slug,
        ])->toArray();

        $this->saveQuietly();
    }

    /**
     * Scope for published public posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }
}
