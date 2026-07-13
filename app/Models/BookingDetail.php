<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'football_field_id', 'booking_date', 'time_slot_id', 'price'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function footballField()
    {
        return $this->belongsTo(FootballField::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }
}
