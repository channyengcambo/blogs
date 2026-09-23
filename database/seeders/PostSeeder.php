<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first() ?? User::first();
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'is_admin' => true,
            ]);
        }

        $categories = Category::all();
        $tags = Tag::all();

        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        if ($tags->isEmpty()) {
            $this->call(TagSeeder::class);
            $tags = Tag::all();
        }

        $postsData = [
            [
                'title' => 'Building High-Performance APIs with Laravel 12 and Octane',
                'sub_title' => 'Supercharge your backend throughput by running Laravel in a persistent worker memory model.',
                'category_name' => 'Web Development',
                'tags' => ['Laravel', 'PHP', 'Backend', 'Performance', 'API'],
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views_count' => 3420,
                'likes_count' => 184,
                'comments_count' => 29,
                'shares_count' => 45,
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'body' => "Laravel 12 paired with FrankenPHP or Swoole via Laravel Octane unlocks unprecedented request speeds and concurrency. By keeping the application booted in RAM across requests, bootstrap overhead drops to almost zero.\n\nIn this comprehensive benchmark and guide, we delve into worker lifecycle management, avoiding memory leaks with singleton instances, and denormalizing database reads to achieve sub-millisecond response times.",
            ],
            [
                'title' => 'Denormalization Strategies for High-Traffic Content Management Systems',
                'sub_title' => 'Why storing computed category names and cached tag JSON directly on posts boosts read scale by 10x.',
                'category_name' => 'Web Development',
                'tags' => ['Database', 'PostgreSQL', 'Performance', 'Backend', 'Architecture'],
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views_count' => 5210,
                'likes_count' => 312,
                'comments_count' => 48,
                'shares_count' => 76,
                'featured_image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=1200&q=80',
                'body' => "Relational database normalization is essential for transactional integrity, but content-heavy read workloads suffer under repetitive multi-table joins. By pre-computing foreign relationship snapshots directly onto the root document, zero-join queries can be served instantly.\n\nHere we explore the denormalization approach implemented in our blog architecture, detailing model events, cache synchronization, and indexing strategies.",
            ],
            [
                'title' => 'Mastering Tailwind CSS and Dark Mode Aesthetics in Modern SaaS',
                'sub_title' => 'Creating luminous dark palettes, translucent glass borders, and refined micro-interactions.',
                'category_name' => 'UI/UX Design',
                'tags' => ['Tailwind CSS', 'UI Design', 'Frontend', 'CSS'],
                'status' => 'published',
                'published_at' => now()->subDays(9),
                'views_count' => 4890,
                'likes_count' => 275,
                'comments_count' => 33,
                'shares_count' => 52,
                'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
                'body' => "Dark mode is no longer just a color invert; it is an immersive spatial experience. By utilizing deep slate undertones, subtle 1px border glows, and contextual color accents, software feels sleek, professional, and tactile.\n\nLearn how to construct a unified design token system that seamlessly toggles themes and elevates user engagement.",
            ],
            [
                'title' => 'Next-Generation Autonomous AI Agents: Architecture and Patterns',
                'sub_title' => 'From reactive LLM completions to long-running proactive task executors with tool capabilities.',
                'category_name' => 'Artificial Intelligence',
                'tags' => ['AI', 'Machine Learning', 'Automation', 'Python'],
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'views_count' => 8410,
                'likes_count' => 540,
                'comments_count' => 92,
                'shares_count' => 118,
                'featured_image' => 'https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=1200&q=80',
                'body' => "Autonomous agents represent the next major evolution in software engineering. By augmenting foundation models with tool execution, memory compaction, and structured reasoning loops, agents can solve non-trivial software engineering tasks autonomously.\n\nWe examine loop architectures, subagent delegation patterns, and verification mechanisms.",
            ],
            [
                'title' => 'Zero-Downtime Deployment with Docker and Kamal on Bare Metal',
                'sub_title' => 'Ditching expensive PaaS platforms for self-hosted container orchestration that just works.',
                'category_name' => 'Cloud & DevOps',
                'tags' => ['Docker', 'DevOps', 'Cloud', 'Kubernetes'],
                'status' => 'published',
                'published_at' => now()->subDays(16),
                'views_count' => 2890,
                'likes_count' => 140,
                'comments_count' => 19,
                'shares_count' => 31,
                'featured_image' => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?auto=format&fit=crop&w=1200&q=80',
                'body' => "Deploying modern web applications shouldn't require thousands of dollars in cloud vendor lock-in. Kamal brings Docker-based zero-downtime rolling deploys to any standard Linux server.\n\nThis guide covers Traefik reverse-proxy setup, SSL certificate automation, and asset caching strategies.",
            ],
            [
                'title' => 'Draft: Integrating Real-Time WebSockets for Live Collaborative Editing',
                'sub_title' => 'An upcoming deep dive into Laravel Reverb and operational transformation for rich text documents.',
                'category_name' => 'Web Development',
                'tags' => ['Laravel', 'WebSockets', 'Realtime', 'Frontend'],
                'status' => 'draft',
                'published_at' => null,
                'views_count' => 0,
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'featured_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                'body' => "Work in progress article exploring Laravel Reverb's native WebSocket server, connection scaling, and conflict resolution algorithms for collaborative document workflows.",
            ],
            [
                'title' => 'Draft: Comprehensive Guide to Threat Modeling in Web Applications',
                'sub_title' => 'Proactive vulnerability identification before shipping critical production code.',
                'category_name' => 'Cybersecurity',
                'tags' => ['Security', 'Backend', 'Architecture'],
                'status' => 'draft',
                'published_at' => null,
                'views_count' => 0,
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'featured_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
                'body' => "Security cannot be an afterthought in contemporary web engineering. In this upcoming guide, we formulate STRIDE threat models and concrete automated penetration test workflows.",
            ],
            [
                'title' => 'Optimizing Mobile App Performance with Flutter 3.24 and Impeller Engine',
                'sub_title' => 'Eliminating animation jank and optimizing memory footprints across iOS and Android.',
                'category_name' => 'Mobile Engineering',
                'tags' => ['Mobile', 'Flutter', 'Performance', 'Frontend'],
                'status' => 'published',
                'published_at' => now()->subDays(22),
                'views_count' => 1950,
                'likes_count' => 98,
                'comments_count' => 12,
                'shares_count' => 18,
                'featured_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1200&q=80',
                'body' => "Impeller replaces the legacy Skia rendering engine to completely eliminate shader compilation stutter. Here is our real-world migration case study and benchmark analysis.",
            ],
        ];

        foreach ($postsData as $data) {
            $category = Category::where('name', $data['category_name'])->first() ?? $categories->first();
            
            $post = Post::updateOrCreate(
                ['title' => $data['title']],
                [
                    'user_id' => $admin->id,
                    'author_name' => $admin->name,
                    'category_id' => $category?->id,
                    'category_name' => $category?->name,
                    'category_slug' => $category?->slug,
                    'sub_title' => $data['sub_title'],
                    'body' => $data['body'],
                    'featured_image' => $data['featured_image'],
                    'status' => $data['status'],
                    'published_at' => $data['published_at'],
                    'views_count' => $data['views_count'],
                    'likes_count' => $data['likes_count'],
                    'comments_count' => $data['comments_count'],
                    'shares_count' => $data['shares_count'],
                    'created_by' => $admin->id,
                ]
            );

            // Find matching tag IDs
            $matchedTagIds = Tag::whereIn('name', $data['tags'])->pluck('id')->toArray();
            if (empty($matchedTagIds)) {
                $matchedTagIds = $tags->random(min(3, $tags->count()))->pluck('id')->toArray();
            }

            $post->syncTagsWithCache($matchedTagIds);
        }
    }
}
