<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Make sub_title nullable
            $table->string('sub_title')->nullable()->change();

            // Denormalized category slug and author name for fast lookups
            $table->string('category_slug')->nullable()->after('category_name');
            $table->string('author_name')->nullable()->after('user_id');

            // Denormalized JSON cache for tags to eliminate post_tags joins on listing
            $table->json('tags_cache')->nullable()->after('featured_image');

            // Publishing & Workflow status
            $table->string('status', 30)->default('draft')->after('tags_cache')->index();
            $table->timestamp('published_at')->nullable()->after('status')->index();
            $table->unsignedSmallInteger('reading_time')->default(1)->after('published_at');

            // Scalable engagement counters for future like/comment/share/views features
            $table->unsignedInteger('views_count')->default(0)->after('reading_time')->index();
            $table->unsignedInteger('likes_count')->default(0)->after('views_count');
            $table->unsignedInteger('comments_count')->default(0)->after('likes_count');
            $table->unsignedInteger('shares_count')->default(0)->after('comments_count');

            // High-performance composite index for the primary public feed query
            $table->index(['status', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropColumn([
                'category_slug',
                'author_name',
                'tags_cache',
                'status',
                'published_at',
                'reading_time',
                'views_count',
                'likes_count',
                'comments_count',
                'shares_count',
            ]);
            $table->string('sub_title')->nullable(false)->change();
        });
    }
};
