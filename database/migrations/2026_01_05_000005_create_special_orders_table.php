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
        Schema::create('special_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->string('subject');
            $table->text('content');
            $table->enum('classification', ['ลับ', 'ลับมาก', 'ลับที่สุด'])->default('ลับ');
            $table->date('effective_date')->nullable();
            $table->enum('status', ['draft', 'active', 'cancelled', 'expired'])->default('draft');
            $table->string('attachment_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('order_date');
            $table->index('order_number');
            $table->index('status');
            $table->index('classification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_orders');
    }
};
