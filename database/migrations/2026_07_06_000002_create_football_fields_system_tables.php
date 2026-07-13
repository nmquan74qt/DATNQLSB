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
        // 1. field_types
        Schema::create('field_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sân 5 người, Sân 7 người, Sân 11 người
            $table->text('description')->nullable();
            $table->decimal('price_per_hour', 12, 2);
            $table->timestamps();
        });

        // 2. football_fields
        Schema::create('football_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_type_id')->constrained('field_types')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('available'); // available, maintenance, occupied
            $table->timestamps();
        });

        // 3. time_slots
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "06:00 - 07:30"
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price_multiplier', 4, 2)->default(1.00); // multiplier for peak hours
            $table->timestamps();
        });

        // 4. bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->string('status')->default('pending'); // pending, confirmed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['booking_date', 'status']);
        });

        // 5. booking_details
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('football_field_id')->constrained('football_fields')->cascadeOnDelete();
            $table->date('booking_date');
            $table->foreignId('time_slot_id')->constrained('time_slots')->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->timestamps();

            // Prevent double booking of same field at same date and timeslot
            $table->unique(['football_field_id', 'booking_date', 'time_slot_id'], 'unique_field_booking_slot');
        });

        // 6. services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nước suối, Thuê bóng, Thuê giày, v.v.
            $table->string('unit'); // Chai, Quả, Đôi, v.v.
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 7. service_orders
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });

        // 8. payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // cash, bank_transfer
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 9. invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // staff/manager who issued
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('final_amount', 12, 2);
            $table->string('status')->default('unpaid'); // unpaid, paid
            $table->timestamps();
        });

        // 10. invoice_details
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('item_name'); // e.g. "Sân A - Sân 5 người (08:00 - 09:30)", "Nước suối"
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('services');
        Schema::dropIfExists('booking_details');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('time_slots');
        Schema::dropIfExists('football_fields');
        Schema::dropIfExists('field_types');
    }
};
