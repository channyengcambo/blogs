<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
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
        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = app(SlugService::class)
                    ->generate($tag->name, static::class);
            }

            if (Auth::check()) {
                $tag->created_by ??= Auth::id();
            }
        });

        static::updating(function (Tag $tag) {
            if ($tag->isDirty('name')) {
                $tag->slug = app(SlugService::class)
                    ->generate($tag->name, static::class, $tag->id);
            }

            if (Auth::check()) {
                $tag->updated_by = Auth::id();
            }
        });

        static::deleting(function (Tag $tag) {
            if (Auth::check() && ! $tag->isForceDeleting()) {
                $tag->deleted_by = Auth::id();
                $tag->saveQuietly();
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
