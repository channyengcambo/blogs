<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for a given model and source string.
     *
     * @param  string  $source       The string to slugify (e.g. "Hello World")
     * @param  class-string<Model>  $modelClass  The model to check uniqueness against
     * @param  int|null  $ignoreId   ID to ignore (for updates)
     * @param  string  $column       The slug column name
     */
    public function generate(
        string $source,
        string $modelClass,
        ?int $ignoreId = null,
        string $column = 'slug'
    ): string {
        $slug = Str::slug($source);
        $original = $slug;
        $counter = 1;

        while ($this->slugExists($modelClass, $column, $slug, $ignoreId)) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(
        string $modelClass,
        string $column,
        string $slug,
        ?int $ignoreId
    ): bool {
        $query = $modelClass::withTrashed()->where($column, $slug);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
