<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time', 'price_multiplier'];

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
