<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BlogFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_with_published_posts(): void
    {
        $category = Category::factory()->create(['name' => 'Architecture']);
        Post::factory()->count(4)->published()->create([
            'category_id' => $category->id,
            'category_name' => $category->name,
        ]);
        Post::factory()->count(2)->draft()->create(); // Drafts should NOT show

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Frontend/Home')
            ->has('posts.data', 4)
            ->has('categories')
            ->has('trendingPosts')
        );
    }

    public function test_homepage_search_filters_posts(): void
    {
        Post::factory()->published()->create(['title' => 'Kubernetes Distributed Clusters']);
        Post::factory()->published()->create(['title' => 'Laravel Zero-Join Cache']);

        $response = $this->get(route('home', ['search' => 'Kubernetes']));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Frontend/Home')
            ->has('posts.data', 1)
            ->where('posts.data.0.title', 'Kubernetes Distributed Clusters')
        );
    }

    public function test_homepage_category_filter(): void
    {
        $cat1 = Category::factory()->create(['name' => 'AI', 'slug' => 'ai']);
        $cat2 = Category::factory()->create(['name' => 'DevOps', 'slug' => 'devops']);

        Post::factory()->published()->create(['category_id' => $cat1->id, 'category_name' => $cat1->name, 'category_slug' => $cat1->slug]);
        Post::factory()->published()->create(['category_id' => $cat2->id, 'category_name' => $cat2->name, 'category_slug' => $cat2->slug]);

        $response = $this->get(route('home', ['category' => 'ai']));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Frontend/Home')
            ->has('posts.data', 1)
            ->where('posts.data.0.category_slug', 'ai')
        );
    }

    public function test_post_detail_page_loads_and_increments_views(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->published()->create([
            'category_id' => $category->id,
            'views_count' => 10,
        ]);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Frontend/PostDetail')
            ->where('post.id', $post->id)
            ->has('relatedPosts')
        );

        $this->assertEquals(11, $post->fresh()->views_count);
    }

    public function test_cannot_view_unpublished_post_on_frontend(): void
    {
        $draft = Post::factory()->draft()->create();

        $response = $this->get(route('posts.show', $draft->slug));

        $response->assertNotFound();
    }

    public function test_user_can_like_post(): void
    {
        $post = Post::factory()->published()->create(['likes_count' => 5]);

        $response = $this->postJson(route('posts.like', $post));

        $response->assertOk();
        $response->assertJson(['success' => true, 'likes_count' => 6]);
        $this->assertEquals(6, $post->fresh()->likes_count);
    }

    public function test_user_can_subscribe_to_newsletter(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'engineer@techchronicle.dev',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_category_and_tag_shortcut_routes_redirect_to_home(): void
    {
        $response = $this->get(route('frontend.category', 'artificial-intelligence'));
        $response->assertRedirect(route('home', ['category' => 'artificial-intelligence']));

        $responseTag = $this->get(route('frontend.tag', 'react-19'));
        $responseTag->assertRedirect(route('home', ['tag' => 'react-19']));
    }
}
