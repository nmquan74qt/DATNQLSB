<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

#[Signature('booking:cancel-expired')]
#[Description('Cancel pending bookings that have expired (older than 15 minutes)')]
class CancelExpiredBookings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredTime = Carbon::now()->subMinutes(15);
        $bookings = Booking::where('status', 'pending')
                           ->where('created_at', '<', $expiredTime)
                           ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $booking->status = 'cancelled';
            $booking->notes = ($booking->notes ? $booking->notes . "\n" : '') . 'Hệ thống tự động hủy do quá hạn thanh toán/đặt cọc (15 phút).';
            $booking->save();
            $count++;
        }

        $this->info("Cancelled {$count} expired pending bookings.");
    }
}
