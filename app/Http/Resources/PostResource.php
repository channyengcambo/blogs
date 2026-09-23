<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = null;
        if ($this->featured_image) {
            $imageUrl = str_starts_with($this->featured_image, 'http')
                ? $this->featured_image
                : Storage::disk('public')->url($this->featured_image);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'slug' => $this->slug,
            'excerpt' => Str::limit(strip_tags($this->body), 160),
            'body' => $this->body,
            'featured_image' => $imageUrl,
            'category' => $this->category_id ? [
                'id' => $this->category_id,
                'name' => $this->category_name,
                'slug' => $this->category_slug,
            ] : null,
            'tags' => $this->tags_cache ?? [],
            'author' => [
                'id' => $this->user_id,
                'name' => $this->author_name,
            ],
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'reading_time' => $this->reading_time,
            'metrics' => [
                'views' => $this->views_count,
                'likes' => $this->likes_count,
                'comments' => $this->comments_count,
                'shares' => $this->shares_count,
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
