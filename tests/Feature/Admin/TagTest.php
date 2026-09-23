<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
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
        $response = $this->get(route('admin.tags.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.tags.create'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('admin.tags.store'), ['name' => 'Laravel']);
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('admin.tags.index'));
        $response->assertForbidden();

        $response = $this->actingAs($this->regularUser)->post(route('admin.tags.store'), [
            'name' => 'Forbidden Tag',
        ]);
        $response->assertForbidden();
    }

    public function test_admin_can_view_tag_index(): void
    {
        Tag::factory()->create([
            'name' => 'Microservices',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.tags.index'));

        $response->assertOk();
        $response->assertSee('Microservices');
        $response->assertSee('Tags');
    }

    public function test_admin_can_search_tags(): void
    {
        Tag::factory()->create(['name' => 'Postgres']);
        Tag::factory()->create(['name' => 'Kubernetes']);

        $response = $this->actingAs($this->admin)->get(route('admin.tags.index', ['search' => 'Postgres']));

        $response->assertOk();
        $response->assertSee('Postgres');
        $response->assertDontSee('Kubernetes');
    }

    public function test_admin_can_view_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.tags.create'));

        $response->assertOk();
        $response->assertSee('Create New Tag');
    }

    public function test_admin_can_create_tag(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.tags.store'), [
            'name' => 'GraphQL API',
            'description' => 'Modern data query and manipulation language for APIs.',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $response->assertSessionHas('success', 'Tag created successfully.');

        $this->assertDatabaseHas('tags', [
            'name' => 'GraphQL API',
            'slug' => 'graphql-api',
            'description' => 'Modern data query and manipulation language for APIs.',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_tag_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.tags.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('tags', 0);
    }

    public function test_tag_name_must_be_unique(): void
    {
        Tag::factory()->create(['name' => 'Cybersecurity']);

        $response = $this->actingAs($this->admin)->post(route('admin.tags.store'), [
            'name' => 'Cybersecurity',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_tag_show_page(): void
    {
        $tag = Tag::factory()->create(['name' => 'Serverless']);

        $response = $this->actingAs($this->admin)->get(route('admin.tags.show', $tag));

        $response->assertOk();
        $response->assertSee('Serverless');
        $response->assertSee('/serverless');
    }

    public function test_admin_can_view_edit_page(): void
    {
        $tag = Tag::factory()->create(['name' => 'Redis Cache']);

        $response = $this->actingAs($this->admin)->get(route('admin.tags.edit', $tag));

        $response->assertOk();
        $response->assertSee('Edit Tag: Redis Cache');
    }

    public function test_admin_can_update_tag(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Old Tag',
            'description' => 'Old Description',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.tags.update', $tag), [
            'name' => 'Updated Tag',
            'description' => 'Updated Description',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $response->assertSessionHas('success', 'Tag updated successfully.');

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Updated Tag',
            'slug' => 'updated-tag',
            'description' => 'Updated Description',
            'updated_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_soft_delete_tag(): void
    {
        $tag = Tag::factory()->create(['name' => 'Temporary Tag']);

        $response = $this->actingAs($this->admin)->delete(route('admin.tags.destroy', $tag));

        $response->assertRedirect(route('admin.tags.index'));
        $response->assertSessionHas('success', 'Tag deleted successfully.');

        $this->assertSoftDeleted('tags', [
            'id' => $tag->id,
            'deleted_by' => $this->admin->id,
        ]);
    }

    public function test_json_api_responses(): void
    {
        // JSON Create
        $response = $this->actingAs($this->admin)->postJson(route('admin.tags.store'), [
            'name' => 'API Tag',
            'description' => 'Created via JSON API',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('tag.name', 'API Tag');
        $response->assertJsonPath('tag.slug', 'api-tag');

        $tagId = $response->json('tag.id');

        // JSON Index
        $response = $this->actingAs($this->admin)->getJson(route('admin.tags.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'last_page', 'total'],
        ]);

        // JSON Update
        $response = $this->actingAs($this->admin)->putJson(route('admin.tags.update', $tagId), [
            'name' => 'API Tag Renamed',
        ]);
        $response->assertOk();
        $response->assertJsonPath('tag.name', 'API Tag Renamed');

        // JSON Delete
        $response = $this->actingAs($this->admin)->deleteJson(route('admin.tags.destroy', $tagId));
        $response->assertOk();
        $response->assertJsonPath('message', 'Tag deleted successfully.');
    }
}
