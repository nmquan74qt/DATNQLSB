<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\FieldType;
use App\Models\Field;
use App\Models\TimeSlot;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Admin, Staff, Customer)
        User::create([
            'name' => 'CEO Admin',
            'email' => 'admin@pitchmanage.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0901234567',
        ]);

        $customer = User::create([
            'name' => 'Lê Khách Hàng',
            'email' => 'customer@pitchmanage.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'points' => 1500,
            'level_id' => 2, // Bạc
            'membership_code' => 'PM-CUST-1002',
            'phone' => '0988888888',
        ]);

        // 2. Field Types
        $type5 = FieldType::create(['name' => 'Sân 5 Người', 'slug' => 'san-5-nguoi', 'capacity' => 5]);
        $type7 = FieldType::create(['name' => 'Sân 7 Người', 'slug' => 'san-7-nguoi', 'capacity' => 7]);
        $type11 = FieldType::create(['name' => 'Sân 11 Người', 'slug' => 'san-11-nguoi', 'capacity' => 11]);

        // 3. Fields with Images
        $f1 = Field::create([
            'field_type_id' => $type5->id,
            'name' => 'Sân Cỏ Nhân Tạo A1',
            'slug' => 'san-co-nhan-tao-a1',
            'description' => 'Sân 5 người tiêu chuẩn FIFA, cỏ êm.',
            'base_price' => 300000,
            'status' => 'available',
        ]);
        \Illuminate\Support\Facades\DB::table('field_images')->insert([
            ['field_id' => $f1->id, 'image_path' => 'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?w=800&q=80', 'is_primary' => true]
        ]);

        $f2 = Field::create([
            'field_type_id' => $type7->id,
            'name' => 'Sân Trung Tâm B1',
            'slug' => 'san-trung-tam-b1',
            'description' => 'Sân 7 người, mặt sân siêu rộng, đèn LED cao áp.',
            'base_price' => 500000,
            'status' => 'available',
        ]);
        \Illuminate\Support\Facades\DB::table('field_images')->insert([
            ['field_id' => $f2->id, 'image_path' => 'https://images.unsplash.com/photo-1518605363189-9854359db5a3?w=800&q=80', 'is_primary' => true]
        ]);

        $f3 = Field::create([
            'field_type_id' => $type11->id,
            'name' => 'Sân Vận Động Chính',
            'slug' => 'san-van-dong-chinh',
            'description' => 'Sân 11 chuyên nghiệp.',
            'base_price' => 1200000,
            'status' => 'available',
        ]);
        \Illuminate\Support\Facades\DB::table('field_images')->insert([
            ['field_id' => $f3->id, 'image_path' => 'https://images.unsplash.com/photo-1556816723-1ce827b9ebe1?w=800&q=80', 'is_primary' => true]
        ]);

        // 4. Time Slots (1-hour each from 06:00 to 23:00)
        for ($hour = 6; $hour < 23; $hour++) {
            $startTime = sprintf('%02d:00:00', $hour);
            $endTime = sprintf('%02d:00:00', $hour + 1);
            
            // Giờ vàng: 17h - 20h
            $modifier = 0;
            if ($hour >= 17 && $hour < 19) {
                $modifier = 50000;
            } elseif ($hour >= 19 && $hour < 21) {
                $modifier = 100000;
            }

            TimeSlot::create([
                'start_time' => $startTime,
                'end_time' => $endTime,
                'price_modifier' => $modifier
            ]);
        }

        // 5. Vouchers
        \App\Models\Voucher::create([
            'code' => 'SUMMER2026',
            'name' => 'Khuyến Mãi Hè Sôi Động',
            'description' => 'Giảm 10% cho mọi hóa đơn.',
            'discount_percent' => 10,
            'max_uses' => 100,
            'is_active' => true,
        ]);
        \App\Models\Voucher::create([
            'code' => 'GIAM50K',
            'name' => 'Giảm Trực Tiếp 50K',
            'description' => 'Giảm thẳng 50.000đ.',
            'discount_amount' => 50000,
            'max_uses' => 50,
            'is_active' => true,
        ]);

        // 6. Posts (Blog)
        \App\Models\Post::create([
            'author_id' => 1,
            'title' => 'Khai mạc giải bóng đá phong trào Cúp Mùa Hè 2026',
            'slug' => 'khai-mac-giai-bong-da-mua-he',
            'excerpt' => 'Giải đấu thường niên lớn nhất khu vực với sự tham gia của 32 đội bóng tranh tài.',
            'content' => '<p>Giải đấu thường niên lớn nhất khu vực với sự tham gia của 32 đội bóng tranh tài.</p>',
            'thumbnail' => 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&q=80',
            'status' => 'published'
        ]);

        \App\Models\Post::create([
            'author_id' => 1,
            'title' => 'Chính sách hoàn tiền mới khi hủy sân trước 24h',
            'slug' => 'chinh-sach-hoan-tien-moi',
            'excerpt' => 'Từ tháng 7/2026, khách hàng sẽ được hoàn 100% tiền cọc nếu hủy sân trước 24 giờ.',
            'content' => '<p>Từ tháng 7/2026, khách hàng sẽ được hoàn 100% tiền cọc nếu hủy sân trước 24 giờ.</p>',
            'thumbnail' => 'https://images.unsplash.com/photo-1551958219-acbc608c6477?w=800&q=80',
            'status' => 'published'
        ]);

        // 7. Seed Past Bookings for Revenue Chart (Last 30 days)
        for ($i = 30; $i >= 0; $i--) {
            // Random 1 to 5 bookings per day
            $bookingCount = rand(1, 5);
            $date = now()->subDays($i)->format('Y-m-d');
            
            for ($j = 0; $j < $bookingCount; $j++) {
                $amount = rand(3, 12) * 100000;
                
                $booking = \App\Models\Booking::create([
                    'user_id' => $customer->id,
                    'booking_code' => 'BK' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'booking_date' => $date,
                    'total_amount' => $amount,
                    'status' => 'completed',
                    'notes' => 'Seeded Booking',
                    'created_at' => now()->subDays($i),
                    'updated_at' => now()->subDays($i),
                ]);

                \App\Models\BookingDetail::create([
                    'booking_id' => $booking->id,
                    'field_id' => rand(1, 3),
                    'time_slot_id' => rand(1, 17),
                    'price' => $amount
                ]);

                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'transaction_id' => 'VNP_SEED_' . \Illuminate\Support\Str::random(10),
                    'amount' => $amount,
                    'payment_method' => 'vnpay',
                    'status' => 'success',
                    'created_at' => now()->subDays($i),
                    'updated_at' => now()->subDays($i),
                ]);
            }
        }
    }
}
