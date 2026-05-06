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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // BigInt PK
            
            // Relationships (Foreign Keys)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Author
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Optional Category
            
            // Content fields
            $table->text('content')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('video_provider', 50)->nullable(); // youtube, vimeo, etc.
            
            // SEO & Status
            $table->string('slug', 255)->unique()->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('published')->index();
            
            // Counters (Performance Optimization)
            $table->integer('views_count')->unsigned()->default(0);
            $table->integer('likes_count')->unsigned()->default(0);
            $table->integer('comments_count')->unsigned()->default(0);
            $table->integer('saves_count')->unsigned()->default(0);
            $table->integer('shares_count')->unsigned()->default(0);
            
            // Timestamps
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};