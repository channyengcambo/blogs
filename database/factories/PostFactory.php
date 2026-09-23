<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['published', 'published', 'published', 'draft']);
        $body = fake()->paragraphs(fake()->numberBetween(4, 8), true);
        $wordCount = str_word_count(strip_tags($body));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        $images = [
            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1618401471353-b98aedd04e11?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1534972195531-a756b1126f24?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
        ];

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => rtrim(fake()->sentence(fake()->numberBetween(4, 8)), '.'),
            'sub_title' => fake()->sentence(fake()->numberBetween(8, 14)),
            'body' => $body,
            'featured_image' => fake()->randomElement($images),
            'status' => $status,
            'published_at' => $status === 'published' ? fake()->dateTimeBetween('-3 months', 'now') : null,
            'reading_time' => $readingTime,
            'views_count' => fake()->numberBetween(15, 8500),
            'likes_count' => fake()->numberBetween(0, 420),
            'comments_count' => fake()->numberBetween(0, 85),
            'shares_count' => fake()->numberBetween(0, 45),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
