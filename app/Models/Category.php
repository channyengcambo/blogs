<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = app(SlugService::class)
                    ->generate($category->name, static::class);
            }

            if (Auth::check()) {
                $category->created_by ??= Auth::id();
            }
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = app(SlugService::class)
                    ->generate($category->name, static::class, $category->id);
            }

            if (Auth::check()) {
                $category->updated_by = Auth::id();
            }
        });

        static::deleting(function (Category $category) {
            if (Auth::check() && ! $category->isForceDeleting()) {
                $category->deleted_by = Auth::id();
                $category->saveQuietly();
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
