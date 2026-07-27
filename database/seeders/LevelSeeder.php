<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('levels')->insert([
            [
                'name' => 'Thành viên Đồng',
                'required_points' => 0,
                'badge_icon' => 'fa-solid fa-medal',
                'color_hex' => '#cd7f32', // Bronze
                'discount_percent' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thành viên Bạc',
                'required_points' => 1000,
                'badge_icon' => 'fa-solid fa-medal',
                'color_hex' => '#c0c0c0', // Silver
                'discount_percent' => 5.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thành viên Vàng',
                'required_points' => 3000,
                'badge_icon' => 'fa-solid fa-trophy',
                'color_hex' => '#ffd700', // Gold
                'discount_percent' => 10.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thành viên VIP',
                'required_points' => 10000,
                'badge_icon' => 'fa-solid fa-crown',
                'color_hex' => '#ff00ff', // Diamond/VIP
                'discount_percent' => 20.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
