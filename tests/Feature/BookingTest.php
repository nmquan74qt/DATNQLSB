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
            'max_uses' => 10,
            'used_count' => 0,
            'valid_from' => now()->subDay(),
            'valid_to' => now()->addDays(5),
        ]);

        // 2. Act: Send POST request to check voucher
        $response = $this->postJson(route('voucher.check'), [
            'code' => 'TEST100',
            'total_amount' => 100000
        ]);

        // 3. Assert: Check response structure and discount value
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'voucher' => [
                         'code' => 'TEST100',
                         'discount_amount' => 10000
                     ]
                 ]);
    }

    public function test_check_voucher_fails_when_expired(): void
    {
        $voucher = \App\Models\Voucher::create([
            'code' => 'EXPIRED',
            'discount_amount' => 10000,
            'is_active' => true,
            'max_uses' => 10,
            'used_count' => 0,
            'valid_from' => now()->subDays(10),
            'valid_to' => now()->subDays(1),
        ]);

        $response = $this->postJson(route('voucher.check'), [
            'code' => 'EXPIRED',
            'total_amount' => 100000
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Mã Voucher không hợp lệ, đã hết hạn hoặc hết lượt sử dụng.'
                 ]);
    }
}
