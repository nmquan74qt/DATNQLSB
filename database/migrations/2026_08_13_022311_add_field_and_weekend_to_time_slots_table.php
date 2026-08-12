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
        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreignId('field_id')->nullable()->constrained('fields')->onDelete('cascade')->after('id');
            $table->decimal('weekend_price_modifier', 8, 2)->default(0)->after('price_modifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign(['field_id']);
            $table->dropColumn(['field_id', 'weekend_price_modifier']);
        });
    }
};
