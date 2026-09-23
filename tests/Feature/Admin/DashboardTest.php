<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_admin_can_view_dashboard_with_kpi_and_chart_data(): void
    {
        $category = Category::factory()->create(['name' => 'Architecture']);
        Tag::factory()->count(3)->create();

        Post::factory()->published()->create([
            'category_id' => $category->id,
            'category_name' => $category->name,
            'views_count' => 500,
            'likes_count' => 30,
            'comments_count' => 10,
            'shares_count' => 5,
        ]);

        Post::factory()->draft()->create([
            'category_id' => $category->id,
            'category_name' => $category->name,
            'views_count' => 100,
            'likes_count' => 5,
            'comments_count' => 2,
            'shares_count' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('totalPosts', 2);
        $response->assertViewHas('publishedPosts', 1);
        $response->assertViewHas('draftPosts', 1);
        $response->assertViewHas('totalCategories', 1);
        $response->assertViewHas('totalTags', 3);
        $response->assertViewHas('totalViews', 600);
        $response->assertViewHas('totalEngagements', 53);
        $response->assertViewHas('trendDates');
        $response->assertViewHas('viewsTrend');
        $response->assertViewHas('engagementTrend');
        $response->assertViewHas('categoryLabels');
        $response->assertViewHas('categoryCounts');
        $response->assertViewHas('topPosts');
    }

    public function test_dashboard_returns_json_when_requested(): void
    {
        Category::factory()->create();
        Post::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.dashboard'));

        $response->assertOk();
        $response->assertJsonStructure([
            'kpis' => [
                'total_posts',
                'published_posts',
                'draft_posts',
                'total_categories',
                'total_tags',
                'total_views',
                'total_likes',
                'total_comments',
                'total_shares',
                'total_engagements',
            ],
            'charts' => [
                'timeline' => [
                    'categories',
                    'views',
                    'engagements',
                ],
                'category_distribution' => [
                    'labels',
                    'series',
                ],
            ],
            'top_posts',
            'recent_posts',
        ]);
    }
}
