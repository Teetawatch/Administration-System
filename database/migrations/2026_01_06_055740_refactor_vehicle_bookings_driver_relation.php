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
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            $table->dropColumn(['driver_name', 'driver_phone']);
            $table->foreignId('vehicle_driver_id')->nullable()->after('user_id')->constrained('vehicle_drivers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_bookings', function (Blueprint $table) {
            $table->dropForeign(['vehicle_driver_id']);
            $table->dropColumn('vehicle_driver_id');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
        });
    }
};
