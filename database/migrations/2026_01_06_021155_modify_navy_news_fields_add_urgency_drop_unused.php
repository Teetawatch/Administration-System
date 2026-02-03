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
        Schema::table('navy_news', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'category']);
            $table->enum('urgency', ['normal', 'urgent', 'very_urgent', 'most_urgent'])->default('normal')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('navy_news', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->string('category')->nullable();
            $table->dropColumn('urgency');
        });
    }
};
