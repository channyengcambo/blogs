<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        $categories = [
            [
                'name' => 'Artificial Intelligence',
                'description' => 'Latest breakthroughs in machine learning, deep learning, LLMs, and intelligent autonomous systems.',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Modern frontend frameworks, PHP/Laravel architectures, RESTful APIs, and best development practices.',
            ],
            [
                'name' => 'Cloud & DevOps',
                'description' => 'Docker containers, Kubernetes clusters, CI/CD automated deployment pipelines, and cloud infrastructure.',
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Design systems, modern micro-interactions, typography hierarchy, responsive layouts, and user accessibility.',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Best practices for web application security, authentication patterns, vulnerability auditing, and data encryption.',
            ],
            [
                'name' => 'Mobile Engineering',
                'description' => 'Cross-platform app development using Flutter, React Native, and native mobile optimization.',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Data visualization, analytics pipelines, statistical modeling, and big data transformations.',
            ],
            [
                'name' => 'Productivity & Career',
                'description' => 'Software engineering workflows, developer ergonomics, continuous learning, and tech career growth.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'description' => $cat['description'],
                    'created_by' => $admin?->id,
                ]
            );
        }
    }
}
