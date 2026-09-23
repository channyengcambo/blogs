<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
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
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.categories.create'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('admin.categories.store'), ['name' => 'Tech']);
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.categories.index'));
        $response->assertForbidden();

        $response = $this->actingAs($this->regularUser)->post(route('admin.categories.store'), [
            'name' => 'Forbidden Category',
        ]);
        $response->assertForbidden();
    }

    public function test_admin_can_view_category_index(): void
    {
        $category = Category::factory()->create([
            'name' => 'Artificial Intelligence',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertSee('Artificial Intelligence');
        $response->assertSee('Categories');
    }

    public function test_admin_can_search_categories(): void
    {
        Category::factory()->create(['name' => 'Design']);
        Category::factory()->create(['name' => 'DevOps']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index', ['search' => 'Design']));

        $response->assertOk();
        $response->assertSee('Design');
        $response->assertDontSee('DevOps');
    }

    public function test_admin_can_view_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));

        $response->assertOk();
        $response->assertSee('New Category');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Machine Learning',
            'description' => 'All about ML models and algorithms.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category created successfully.');

        $this->assertDatabaseHas('categories', [
            'name' => 'Machine Learning',
            'slug' => 'machine-learning',
            'description' => 'All about ML models and algorithms.',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_category_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create(['name' => 'Cybersecurity']);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Cybersecurity',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_category_show_page(): void
    {
        $category = Category::factory()->create(['name' => 'Cloud Computing']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.show', $category));

        $response->assertOk();
        $response->assertSee('Cloud Computing');
        $response->assertSee('/cloud-computing');
    }

    public function test_admin_can_view_edit_page(): void
    {
        $category = Category::factory()->create(['name' => 'Data Science']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.edit', $category));

        $response->assertOk();
        $response->assertSee('Data Science');
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Old Title',
            'description' => 'Old Description',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Updated Title',
            'description' => 'Updated Description',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Title',
            'slug' => 'updated-title',
            'description' => 'Updated Description',
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_soft_delete_category(): void
    {
        $category = Category::factory()->create(['name' => 'Temporary Category']);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully.');

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
            'deleted_by' => $this->admin->id,
        ]);
    }

    public function test_json_api_responses(): void
    {
        // JSON Create
        $response = $this->actingAs($this->admin)->postJson(route('admin.categories.store'), [
            'name' => 'API Category',
            'description' => 'Created via JSON API',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('category.name', 'API Category');
        $response->assertJsonPath('category.slug', 'api-category');

        $categoryId = $response->json('category.id');

        // JSON Index
        $response = $this->actingAs($this->admin)->getJson(route('admin.categories.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'last_page', 'total'],
        ]);

        // JSON Update
        $response = $this->actingAs($this->admin)->putJson(route('admin.categories.update', $categoryId), [
            'name' => 'API Category Renamed',
        ]);
        $response->assertOk();
        $response->assertJsonPath('category.name', 'API Category Renamed');

        // JSON Delete
        $response = $this->actingAs($this->admin)->deleteJson(route('admin.categories.destroy', $categoryId));
        $response->assertOk();
        $response->assertJsonPath('message', 'Category deleted successfully.');
    }

    public function test_categories_index_supports_pagination_and_custom_per_page(): void
    {
        Category::factory()->count(14)->create();

        // Default pagination is 6 per page
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertOk();
        $response->assertViewHas('categories', function ($categories) {
            return $categories->total() === 14 && $categories->count() === 6 && $categories->perPage() === 6;
        });

        // Custom per_page of 12
        $response12 = $this->actingAs($this->admin)->get(route('admin.categories.index', ['per_page' => 12]));
        $response12->assertOk();
        $response12->assertViewHas('categories', function ($categories) {
            return $categories->total() === 14 && $categories->count() === 12 && $categories->perPage() === 12;
        });

        // Page 2 navigation
        $responsePage2 = $this->actingAs($this->admin)->get(route('admin.categories.index', ['page' => 2]));
        $responsePage2->assertOk();
        $responsePage2->assertViewHas('categories', function ($categories) {
            return $categories->currentPage() === 2 && $categories->count() === 6;
        });
    }
}

