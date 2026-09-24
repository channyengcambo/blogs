<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthorPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $author;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'is_admin' => true,
        ]);

        $this->author = User::factory()->create([
            'name' => 'John Developer',
            'is_admin' => false,
        ]);

        $this->otherUser = User::factory()->create([
            'name' => 'Alice Engineer',
            'is_admin' => false,
        ]);
    }

    public function test_normal_user_cannot_access_any_admin_routes(): void
    {
        // Normal user must get 403 on all /admin routes
        $this->actingAs($this->author)->get('/admin')->assertForbidden();
        $this->actingAs($this->author)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->author)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($this->author)->get(route('admin.tags.index'))->assertForbidden();
        $this->actingAs($this->author)->get(route('admin.posts.index'))->assertForbidden();
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_dashboard_redirects_based_on_user_role(): void
    {
        // Admin goes to admin dashboard
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));

        // Normal user goes to author dashboard
        $this->actingAs($this->author)
            ->get(route('dashboard'))
            ->assertRedirect(route('author.dashboard'));
    }

    public function test_author_can_view_dashboard_with_only_their_articles(): void
    {
        $category = Category::factory()->create(['name' => 'DevOps']);

        // Author has 2 posts
        Post::factory()->published()->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'views_count' => 150,
            'likes_count' => 12,
        ]);
        Post::factory()->draft()->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'views_count' => 0,
            'likes_count' => 0,
        ]);

        // Other user has 1 post
        Post::factory()->published()->create([
            'user_id' => $this->otherUser->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->author)->get(route('author.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Author/Dashboard')
            ->has('posts.data', 2)
            ->where('metrics.total_posts', 2)
            ->where('metrics.published_posts', 1)
            ->where('metrics.draft_posts', 1)
            ->where('metrics.total_views', 150)
            ->where('metrics.total_likes', 12)
        );
    }

    public function test_author_can_view_post_creation_form(): void
    {
        Category::factory()->count(3)->create();
        Tag::factory()->count(4)->create();

        $response = $this->actingAs($this->author)->get(route('author.posts.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Author/PostEditor')
            ->where('post', null)
            ->has('categories', 3)
            ->has('tags', 4)
        );
    }

    public function test_author_can_create_and_publish_new_article(): void
    {
        $category = Category::factory()->create(['name' => 'Cloud Systems', 'slug' => 'cloud-systems']);
        $tag = Tag::factory()->create(['name' => 'Docker', 'slug' => 'docker']);

        $postData = [
            'title' => 'Mastering Containerized Distributed Systems',
            'sub_title' => 'A practical guide to multi-region orchestration.',
            'category_id' => $category->id,
            'body' => "## Architecture\n\nContainers provide consistent execution environments.",
            'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800',
            'status' => 'published',
            'tags' => [$tag->id],
        ];

        $response = $this->actingAs($this->author)->post(route('author.posts.store'), $postData);

        $response->assertRedirect(route('author.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'title' => 'Mastering Containerized Distributed Systems',
            'slug' => 'mastering-containerized-distributed-systems',
            'user_id' => $this->author->id,
            'author_name' => $this->author->name,
            'category_name' => 'Cloud Systems',
            'category_slug' => 'cloud-systems',
            'status' => 'published',
        ]);

        $createdPost = Post::where('slug', 'mastering-containerized-distributed-systems')->first();
        $this->assertNotNull($createdPost);
        $this->assertNotNull($createdPost->published_at);
        $this->assertEquals([['id' => $tag->id, 'name' => $tag->name, 'slug' => $tag->slug]], $createdPost->tags_cache);
    }

    public function test_author_can_edit_their_own_article(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'title' => 'Initial Title',
            'body' => 'Initial content',
        ]);

        $response = $this->actingAs($this->author)->get(route('author.posts.edit', $post));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Author/PostEditor')
            ->where('post.id', $post->id)
            ->where('post.title', 'Initial Title')
        );

        $updateResponse = $this->actingAs($this->author)->put(route('author.posts.update', $post), [
            'title' => 'Updated Title Post',
            'sub_title' => 'New summary',
            'category_id' => $category->id,
            'body' => 'Updated article body text.',
            'status' => 'published',
        ]);

        $updateResponse->assertRedirect(route('author.dashboard'));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title Post',
        ]);
    }

    public function test_user_cannot_edit_another_users_article(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id,
            'title' => 'Author Original Article',
        ]);

        // otherUser attempts to view edit page
        $this->actingAs($this->otherUser)
            ->get(route('author.posts.edit', $post))
            ->assertForbidden();

        // otherUser attempts to update
        $this->actingAs($this->otherUser)
            ->put(route('author.posts.update', $post), [
                'title' => 'Hacked Title',
                'body' => 'Hacked body',
                'status' => 'published',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Author Original Article',
        ]);
    }

    public function test_user_cannot_delete_another_users_article(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id,
        ]);

        $this->actingAs($this->otherUser)
            ->delete(route('author.posts.destroy', $post))
            ->assertForbidden();

        $this->assertNotSoftDeleted($post);
    }

    public function test_author_can_delete_their_own_article(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->author)->delete(route('author.posts.destroy', $post));

        $response->assertRedirect(route('author.dashboard'));
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }
}
