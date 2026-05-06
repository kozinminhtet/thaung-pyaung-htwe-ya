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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');

            // Types
            $table->enum('type', ['like', 'comment', 'save', 'share'])->index();

            // Comment
            $table->text('content')->nullable();

            // Reply system
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->foreign('parent_id')
                ->references('id')
                ->on('interactions')
                ->onDelete('cascade');

            // Laravel standard
            $table->timestamps();

            // VERY IMPORTANT
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
