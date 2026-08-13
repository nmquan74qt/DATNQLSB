<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_access_checkout_page(): void
    {
        $response = $this->get('/checkout');

        $response->assertStatus(200);
    }
}
