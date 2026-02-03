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
        Schema::table('outgoing_documents', function (Blueprint $table) {
            $table->enum('urgency', ['normal', 'urgent', 'very_urgent', 'most_urgent'])->default('normal')->after('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_documents', function (Blueprint $table) {
            $table->dropColumn('urgency');
        });
    }
};
