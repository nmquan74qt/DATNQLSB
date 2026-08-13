<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_checkout_page(): void
    {
        $response = $this->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_check_voucher_returns_discount_when_valid(): void
    {
        // 1. Arrange: Create a valid voucher
        $voucher = \App\Models\Voucher::create([
            'code' => 'TEST100',
            'discount_amount' => 10000,
            'is_active' => true,
            'usage_limit' => 10,
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(5),
        ]);

        // 2. Act: Send POST request to check voucher
        $response = $this->postJson(route('api.vouchers.check'), [
            'code' => 'TEST100',
            'total_amount' => 100000
        ]);

        // 3. Assert: Check response structure and discount value
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'discount_amount' => 10000
                 ]);
    }

    public function test_check_voucher_fails_when_expired(): void
    {
        $voucher = \App\Models\Voucher::create([
            'code' => 'EXPIRED',
            'discount_amount' => 10000,
            'is_active' => true,
            'usage_limit' => 10,
            'used_count' => 0,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(1),
        ]);

        $response = $this->postJson(route('api.vouchers.check'), [
            'code' => 'EXPIRED',
            'total_amount' => 100000
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Mã giảm giá đã hết hạn.'
                 ]);
    }
}
