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
        Schema::create('navy_news', function (Blueprint $table) {
            $table->id();
            $table->string('news_number')->unique();
            $table->date('news_date');
            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('attachment_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('news_date');
            $table->index('is_published');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navy_news');
    }
};
