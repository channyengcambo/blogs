<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'is_admin' => false,
        ]);
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get(route('admin.posts.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.posts.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_view_posts_index_with_kpi_metrics(): void
    {
        $category = Category::factory()->create(['name' => 'Backend Development']);
        Post::factory()->count(3)->published()->create([
            'category_id' => $category->id,
            'category_name' => $category->name,
            'views_count' => 100,
        ]);
        Post::factory()->count(2)->draft()->create([
            'category_id' => $category->id,
            'category_name' => $category->name,
            'views_count' => 50,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.index'));

        $response->assertOk();
        $response->assertViewIs('admin.posts.index');
        $response->assertViewHas('totalPosts', 5);
        $response->assertViewHas('publishedPosts', 3);
        $response->assertViewHas('draftPosts', 2);
        $response->assertViewHas('totalViews', 400);
    }

    public function test_admin_can_filter_posts_by_status(): void
    {
        Post::factory()->published()->create(['title' => 'Published Article Title']);
        Post::factory()->draft()->create(['title' => 'Draft Article Title']);

        // Test Published Filter
        $response = $this->actingAs($this->admin)->get(route('admin.posts.index', ['status' => 'published']));
        $response->assertOk();
        $response->assertSee('Published Article Title');
        $response->assertDontSee('Draft Article Title');

        // Test Draft Filter
        $response = $this->actingAs($this->admin)->get(route('admin.posts.index', ['status' => 'draft']));
        $response->assertOk();
        $response->assertSee('Draft Article Title');
        $response->assertDontSee('Published Article Title');
    }

    public function test_admin_can_filter_posts_by_category(): void
    {
        $cat1 = Category::factory()->create(['name' => 'AI Engineering']);
        $cat2 = Category::factory()->create(['name' => 'Cloud Infrastructure']);

        Post::factory()->create([
            'title' => 'Deep Dive into Transformers',
            'category_id' => $cat1->id,
            'category_name' => $cat1->name,
        ]);

        Post::factory()->create([
            'title' => 'Kubernetes Pod Autoscaling',
            'category_id' => $cat2->id,
            'category_name' => $cat2->name,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.index', ['category_id' => $cat1->id]));
        $response->assertOk();
        $response->assertSee('Deep Dive into Transformers');
        $response->assertDontSee('Kubernetes Pod Autoscaling');
    }

    public function test_admin_can_search_posts(): void
    {
        Post::factory()->create(['title' => 'Unique Query Match Target']);
        Post::factory()->create(['title' => 'Completely Different Post']);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.index', ['search' => 'Query Match']));
        $response->assertOk();
        $response->assertSee('Unique Query Match Target');
        $response->assertDontSee('Completely Different Post');
    }

    public function test_admin_can_view_post_details(): void
    {
        $category = Category::factory()->create(['name' => 'DevOps']);
        $tag = Tag::factory()->create(['name' => 'Docker']);

        $post = Post::factory()->create([
            'title' => 'Comprehensive Docker Guide',
            'category_id' => $category->id,
            'category_name' => $category->name,
            'category_slug' => $category->slug,
            'sub_title' => 'Learn containers step by step',
            'views_count' => 1250,
            'likes_count' => 45,
        ]);
        $post->syncTagsWithCache([$tag->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.show', $post));

        $response->assertOk();
        $response->assertViewIs('admin.posts.show');
        $response->assertSee('Comprehensive Docker Guide');
        $response->assertSee('Learn containers step by step');
        $response->assertSee('DevOps');
        $response->assertSee('#Docker');
        $response->assertSee('1,250');
    }

    public function test_admin_can_toggle_post_status(): void
    {
        $post = Post::factory()->draft()->create();
        $this->assertEquals('draft', $post->status);

        // Toggle from draft to published
        $response = $this->actingAs($this->admin)->patch(route('admin.posts.toggle-status', $post));
        $response->assertRedirect();
        $this->assertEquals('published', $post->fresh()->status);
        $this->assertNotNull($post->fresh()->published_at);

        // Toggle from published back to draft
        $response = $this->actingAs($this->admin)->patch(route('admin.posts.toggle-status', $post));
        $response->assertRedirect();
        $this->assertEquals('draft', $post->fresh()->status);
    }

    public function test_admin_can_soft_delete_post(): void
    {
        $post = Post::factory()->create(['title' => 'Article To Be Deleted']);

        $response = $this->actingAs($this->admin)->delete(route('admin.posts.destroy', $post));
        $response->assertRedirect(route('admin.posts.index'));

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_posts_index_returns_json_resource_when_requested(): void
    {
        Post::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.posts.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'category',
                    'tags',
                    'status',
                    'metrics',
                ],
            ],
            'meta' => ['total', 'current_page'],
            'kpi' => ['total_posts', 'published_posts', 'draft_posts', 'total_views'],
        ]);
    }

    public function test_post_auto_denormalizes_category_and_author(): void
    {
        $category = Category::factory()->create(['name' => 'Machine Learning', 'slug' => 'machine-learning']);

        $this->actingAs($this->admin);
        $newPost = Post::create([
            'user_id' => $this->admin->id,
            'category_id' => $category->id,
            'title' => 'Neural Network Foundations',
            'body' => 'Introduction to multi-layer perceptrons and backpropagation.',
            'status' => 'published',
        ]);

        $this->assertEquals($category->name, $newPost->category_name);
        $this->assertEquals($category->slug, $newPost->category_slug);
        $this->assertEquals($this->admin->name, $newPost->author_name);
        $this->assertNotNull($newPost->reading_time);
        $this->assertEquals('neural-network-foundations', $newPost->slug);
    }

    public function test_post_tags_cache_synchronization(): void
    {
        $tag1 = Tag::factory()->create(['name' => 'PHP', 'slug' => 'php']);
        $tag2 = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

        $post = Post::factory()->create();
        $post->syncTagsWithCache([$tag1->id, $tag2->id]);

        $cached = $post->fresh()->tags_cache;
        $this->assertIsArray($cached);
        $this->assertCount(2, $cached);
        $this->assertEquals('PHP', $cached[0]['name']);
        $this->assertEquals('Laravel', $cached[1]['name']);
    }
}
