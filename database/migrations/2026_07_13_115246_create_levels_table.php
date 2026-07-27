<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Đồng, Bạc, Vàng, Kim Cương
            $table->integer('required_points'); // Points needed to reach this level
            $table->string('badge_icon')->nullable(); // Icon for the badge
            $table->string('color_hex')->nullable(); // For UI styling
            $table->decimal('discount_percent', 5, 2)->default(0); // Perks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
