<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'type')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('type', 20)->nullable()->index();
            });
        }

        if (! Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0);
            });
        }
    }

    // Keep these additive columns on rollback to avoid dropping category metadata.
    public function down(): void
    {
        // Intentionally non-destructive.
    }
};
