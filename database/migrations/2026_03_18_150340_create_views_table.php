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
        Schema::create('views', function (Blueprint $table) {
            $table->id(); // BigInt PK

            // Relationships
            $table->foreignId('post_id')->constrained()->onDelete('cascade');

            // Nullable user (guest allowed)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // IPv4 / IPv6
            $table->string('ip_address', 45)->index();

            // Device / browser info (optional but useful)
            $table->string('user_agent', 255)->nullable();

            // Core anti-spam field (IMPORTANT)
            $table->date('view_date')->index();

            // Laravel standard
            $table->timestamps();

            //Prevent duplicate views (per day per IP)
            $table->unique(
                ['post_id', 'ip_address', 'view_date'],
                'unique_view_per_day_ip'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
