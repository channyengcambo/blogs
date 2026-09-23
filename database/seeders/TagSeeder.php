<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        $tags = [
            ['name' => 'Laravel 12', 'description' => 'The PHP framework for web artisans with elegant syntax and powerful features.'],
            ['name' => 'PHP 8.4', 'description' => 'Modern PHP features, property hooks, type safety, and runtime optimizations.'],
            ['name' => 'React 19', 'description' => 'Component-based user interface library with server actions and concurrency.'],
            ['name' => 'Next.js', 'description' => 'Full-stack React framework with SSR, App Router, and edge rendering.'],
            ['name' => 'TailwindCSS', 'description' => 'Utility-first CSS framework for rapid and modern UI prototyping.'],
            ['name' => 'Docker', 'description' => 'Containerization platform for seamless local dev and production parity.'],
            ['name' => 'PostgreSQL', 'description' => 'Advanced open-source relational database with robust JSONB and indexing.'],
            ['name' => 'TypeScript', 'description' => 'Typed superset of JavaScript that compiles to clean plain JavaScript.'],
            ['name' => 'REST API', 'description' => 'Architectural design patterns for building scalable and decoupled web services.'],
            ['name' => 'DevOps', 'description' => 'Continuous integration, delivery pipelines, observability, and infrastructure automation.'],
            ['name' => 'AI & LLMs', 'description' => 'Generative AI models, prompt engineering, agentic workflows, and embeddings.'],
            ['name' => 'UI/UX', 'description' => 'User experience principles, accessibility standards, and visual design.'],
        ];

        foreach ($tags as $tagData) {
            Tag::updateOrCreate(
                ['name' => $tagData['name']],
                [
                    'description' => $tagData['description'],
                    'created_by' => $admin?->id,
                ]
            );
        }
    }
}
