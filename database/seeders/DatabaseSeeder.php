<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\FieldType;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\ServiceOrder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $managerRole = Role::create([
            'name' => 'manager',
            'description' => 'Quản trị viên có toàn quyền kiểm soát hệ thống'
        ]);

        $staffRole = Role::create([
            'name' => 'staff',
            'description' => 'Nhân viên quản lý lịch đặt sân, dịch vụ và thanh toán'
        ]);

        $customerRole = Role::create([
            'name' => 'customer',
            'description' => 'Khách hàng đặt sân và sử dụng dịch vụ'
        ]);

        // 2. Create Users
        $manager = User::create([
            'role_id' => $managerRole->id,
            'name' => 'Quản lý Hệ Thống',
            'email' => 'manager@gmail.com',
            'phone' => '0987654321',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        $staff = User::create([
            'role_id' => $staffRole->id,
            'name' => 'Nguyễn Văn Nhân Viên',
            'email' => 'staff@gmail.com',
            'phone' => '0912345678',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        $customer = User::create([
            'role_id' => $customerRole->id,
            'name' => 'Trần Văn Khách Hàng',
            'email' => 'customer@gmail.com',
            'phone' => '0909090909',
            'password' => Hash::make('password'),
            'status' => 'active'
        ]);

        // Add 5 more sample customers
        $customers = [$customer];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = User::create([
                'role_id' => $customerRole->id,
                'name' => "Khách Hàng Sample $i",
                'email' => "customer$i@gmail.com",
                'phone' => "090000000$i",
                'password' => Hash::make('password'),
                'status' => 'active'
            ]);
        }

        // Add 1 locked customer for testing locking functionality
        User::create([
            'role_id' => $customerRole->id,
            'name' => 'Khách Hàng Bị Khóa',
            'email' => 'locked@gmail.com',
            'phone' => '0911111111',
            'password' => Hash::make('password'),
            'status' => 'locked'
        ]);

        // 3. Create Field Types
        $type5 = FieldType::create([
            'name' => 'Sân 5 người',
            'description' => 'Sân cỏ nhân tạo mini, thích hợp đấu giao hữu nhỏ, di chuyển linh hoạt.',
            'price_per_hour' => 200000.00
        ]);

        $type7 = FieldType::create([
            'name' => 'Sân 7 người',
            'description' => 'Sân cỏ nhân tạo cỡ trung, kích thước chuẩn cho giải đấu phong trào.',
            'price_per_hour' => 300000.00
        ]);

        $type11 = FieldType::create([
            'name' => 'Sân 11 người',
            'description' => 'Sân cỏ nhân tạo lớn tiêu chuẩn 11 người, thích hợp cho các trận đấu lớn hoặc sự kiện.',
            'price_per_hour' => 500000.00
        ]);

        // 4. Create Football Fields
        $fields = [];
        $fields[] = FootballField::create([
            'field_type_id' => $type5->id,
            'name' => 'Sân Mini A1',
            'description' => 'Sân mini 5 người gần cổng ra vào, chất lượng cỏ tốt.',
            'image' => null,
            'status' => 'available'
        ]);

        $fields[] = FootballField::create([
            'field_type_id' => $type5->id,
            'name' => 'Sân Mini A2',
            'description' => 'Sân mini 5 người, hệ thống chiếu sáng LED hiện đại.',
            'image' => null,
            'status' => 'available'
        ]);

        $fields[] = FootballField::create([
            'field_type_id' => $type5->id,
            'name' => 'Sân Mini A3',
            'description' => 'Sân mini 5 người, chất lượng thoát nước tốt.',
            'image' => null,
            'status' => 'available'
        ]);

        $fields[] = FootballField::create([
            'field_type_id' => $type7->id,
            'name' => 'Sân Trung B1',
            'description' => 'Sân 7 người, cỏ nhập khẩu cao cấp.',
            'image' => null,
            'status' => 'available'
        ]);

        $fields[] = FootballField::create([
            'field_type_id' => $type7->id,
            'name' => 'Sân Trung B2',
            'description' => 'Sân 7 người, khu khán đài nhỏ bên cạnh.',
            'image' => null,
            'status' => 'available'
        ]);

        $fields[] = FootballField::create([
            'field_type_id' => $type11->id,
            'name' => 'Sân Đại C1',
            'description' => 'Sân lớn 11 người, đạt tiêu chuẩn chất lượng cao nhất.',
            'image' => null,
            'status' => 'available'
        ]);

        // 5. Create Time Slots
        $slots = [
            ['name' => '06:00 - 07:30', 'start' => '06:00:00', 'end' => '07:30:00', 'multiplier' => 1.00],
            ['name' => '07:30 - 09:00', 'start' => '07:30:00', 'end' => '09:00:00', 'multiplier' => 1.00],
            ['name' => '09:00 - 10:30', 'start' => '09:00:00', 'end' => '10:30:00', 'multiplier' => 1.00],
            ['name' => '10:30 - 12:00', 'start' => '10:30:00', 'end' => '12:00:00', 'multiplier' => 1.00],
            ['name' => '13:30 - 15:00', 'start' => '13:30:00', 'end' => '15:00:00', 'multiplier' => 1.00],
            ['name' => '15:00 - 16:30', 'start' => '15:00:00', 'end' => '16:30:00', 'multiplier' => 1.20], // peak starts
            ['name' => '16:30 - 18:00', 'start' => '16:30:00', 'end' => '18:00:00', 'multiplier' => 1.50], // high peak
            ['name' => '18:00 - 19:30', 'start' => '18:00:00', 'end' => '19:30:00', 'multiplier' => 1.50], // high peak
            ['name' => '19:30 - 21:00', 'start' => '19:30:00', 'end' => '21:00:00', 'multiplier' => 1.50], // high peak
            ['name' => '21:00 - 22:30', 'start' => '21:00:00', 'end' => '22:30:00', 'multiplier' => 1.20],
        ];

        $timeSlots = [];
        foreach ($slots as $slot) {
            $timeSlots[] = TimeSlot::create([
                'name' => $slot['name'],
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'price_multiplier' => $slot['multiplier']
            ]);
        }

        // 6. Create Services
        $water = Service::create([
            'name' => 'Nước suối Aquafina',
            'unit' => 'Chai',
            'price' => 15000.00,
            'stock' => 500,
            'description' => 'Nước tinh khiết 500ml ướp lạnh'
        ]);

        $sting = Service::create([
            'name' => 'Nước Sting đỏ',
            'unit' => 'Chai',
            'price' => 20000.00,
            'stock' => 200,
            'description' => 'Nước tăng lực Sting hương dâu chai 330ml'
        ]);

        $ball = Service::create([
            'name' => 'Thuê bóng Động Lực',
            'unit' => 'Quả / Trận',
            'price' => 30000.00,
            'stock' => 30,
            'description' => 'Bóng đá Động Lực số 4 hoặc số 5'
        ]);

        $bib = Service::create([
            'name' => 'Thuê áo bib tập luyện',
            'unit' => 'Bộ (10 cái) / Trận',
            'price' => 20000.00,
            'stock' => 15,
            'description' => 'Bộ áo bib phân biệt đội hình đấu tập'
        ]);

        $shoes = Service::create([
            'name' => 'Thuê giày đá bóng',
            'unit' => 'Đôi / Trận',
            'price' => 40000.00,
            'stock' => 50,
            'description' => 'Giày đinh TF sân cỏ nhân tạo'
        ]);

        $services = [$water, $sting, $ball, $bib, $shoes];

        // 7. Seed Past Bookings for the last 6 months (Charts & Dashboard data)
        $now = Carbon::now();
        $invoiceCount = 1000;

        // Loop through last 6 months
        for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
            $monthDate = $now->copy()->subMonths($monthOffset);
            
            // In each month, create 8-15 completed bookings
            $bookingsCount = rand(8, 15);
            for ($k = 0; $k < $bookingsCount; $k++) {
                $chosenCustomer = $customers[rand(0, count($customers) - 1)];
                $chosenField = $fields[rand(0, count($fields) - 1)];
                $chosenSlot = $timeSlots[rand(0, count($timeSlots) - 1)];
                
                // Random day in the target month
                $dayOfMonth = rand(1, 28);
                $bookingDate = Carbon::create($monthDate->year, $monthDate->month, $dayOfMonth);
                
                // Calculate field cost
                $fieldHourRate = $chosenField->fieldType->price_per_hour;
                // A slot is 1.5 hours
                $fieldPrice = $fieldHourRate * 1.5 * $chosenSlot->price_multiplier;
                
                // Create Booking
                $booking = Booking::create([
                    'user_id' => $chosenCustomer->id,
                    'customer_name' => $chosenCustomer->name,
                    'customer_phone' => $chosenCustomer->phone,
                    'booking_date' => $bookingDate,
                    'total_amount' => 0, // Will update below
                    'status' => 'completed',
                    'notes' => 'Giao hữu tự do.',
                    'created_at' => $bookingDate->copy()->subDays(rand(2, 5)),
                    'updated_at' => $bookingDate
                ]);

                // Create Booking Detail
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'football_field_id' => $chosenField->id,
                    'booking_date' => $bookingDate,
                    'time_slot_id' => $chosenSlot->id,
                    'price' => $fieldPrice,
                    'created_at' => $booking->created_at,
                    'updated_at' => $bookingDate
                ]);

                $totalAmount = $fieldPrice;

                // Add 1 or 2 random services
                $serviceOrdersCount = rand(1, 2);
                for ($s = 0; $s < $serviceOrdersCount; $s++) {
                    $chosenService = $services[rand(0, count($services) - 1)];
                    $quantity = rand(1, 5);
                    $serviceTotal = $chosenService->price * $quantity;
                    
                    ServiceOrder::create([
                        'booking_id' => $booking->id,
                        'service_id' => $chosenService->id,
                        'quantity' => $quantity,
                        'price' => $chosenService->price,
                        'total_amount' => $serviceTotal,
                        'created_at' => $booking->created_at,
                        'updated_at' => $bookingDate
                    ]);
                    
                    $totalAmount += $serviceTotal;
                }

                // Update Booking Total
                $booking->update(['total_amount' => $totalAmount]);

                // Create Payment
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $totalAmount,
                    'payment_method' => rand(0, 1) ? 'cash' : 'bank_transfer',
                    'payment_status' => 'completed',
                    'transaction_id' => 'TXN' . rand(100000, 999999),
                    'paid_at' => $bookingDate->copy()->addHours(2),
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate
                ]);

                // Create Invoice
                $invoiceNumber = 'HD-' . $bookingDate->format('Ymd') . '-' . $invoiceCount++;
                $invoice = Invoice::create([
                    'booking_id' => $booking->id,
                    'user_id' => $staff->id,
                    'invoice_number' => $invoiceNumber,
                    'customer_name' => $booking->customer_name,
                    'customer_phone' => $booking->customer_phone,
                    'total_amount' => $totalAmount,
                    'discount' => 0.00,
                    'final_amount' => $totalAmount,
                    'status' => 'paid',
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate
                ]);

                // Add Field to Invoice Details
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $chosenField->name . ' - ' . $chosenField->fieldType->name . ' (' . $chosenSlot->name . ')',
                    'quantity' => 1,
                    'price' => $fieldPrice,
                    'total_amount' => $fieldPrice,
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate
                ]);

                // Add Services to Invoice Details
                foreach ($booking->serviceOrders as $so) {
                    InvoiceDetail::create([
                        'invoice_id' => $invoice->id,
                        'item_name' => $so->service->name,
                        'quantity' => $so->quantity,
                        'price' => $so->price,
                        'total_amount' => $so->total_amount,
                        'created_at' => $bookingDate,
                        'updated_at' => $bookingDate
                    ]);
                }
            }
        }

        // 8. Create some Active bookings for Today and Tomorrow (Pending and Confirmed)
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Booking 1: Today, Confirmed, Field A1, Slot 6
        $bookingToday = Booking::create([
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'booking_date' => $today,
            'total_amount' => 360000.00,
            'status' => 'confirmed',
            'notes' => 'Trận đấu tối nay'
        ]);
        BookingDetail::create([
            'booking_id' => $bookingToday->id,
            'football_field_id' => $fields[0]->id, // Sân A1
            'booking_date' => $today,
            'time_slot_id' => $timeSlots[5]->id, // 15:00 - 16:30
            'price' => 360000.00 // 200000 * 1.5 * 1.2
        ]);

        // Booking 2: Tomorrow, Pending, Field B1, Slot 7
        $bookingTmr = Booking::create([
            'user_id' => $customers[2]->id,
            'customer_name' => $customers[2]->name,
            'customer_phone' => $customers[2]->phone,
            'booking_date' => $tomorrow,
            'total_amount' => 675000.00,
            'status' => 'pending',
            'notes' => 'Yêu cầu đấu giao hữu sân 7'
        ]);
        BookingDetail::create([
            'booking_id' => $bookingTmr->id,
            'football_field_id' => $fields[3]->id, // Sân B1 (Sân 7)
            'booking_date' => $tomorrow,
            'time_slot_id' => $timeSlots[6]->id, // 16:30 - 18:00
            'price' => 675000.00 // 300000 * 1.5 * 1.5
        ]);
    }
}
