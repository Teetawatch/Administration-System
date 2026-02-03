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
        Schema::create('vehicle_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Booker
            $table->foreignId('driver_id')->nullable()->constrained('personnel')->nullOnDelete(); // Driver from Personnel
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('destination');
            $table->text('purpose');
            $table->integer('start_mileage')->nullable();
            $table->integer('end_mileage')->nullable();
            $table->decimal('fuel_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_bookings');
    }
};
