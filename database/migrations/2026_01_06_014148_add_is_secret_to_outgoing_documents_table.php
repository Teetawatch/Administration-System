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
            $table->boolean('is_secret')->default(false)->after('document_number');
            $table->index('is_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoing_documents', function (Blueprint $table) {
            $table->dropIndex(['is_secret']);
            $table->dropColumn('is_secret');
        });
    }
};
