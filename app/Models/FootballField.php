<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballField extends Model
{
    use HasFactory;

    protected $fillable = ['field_type_id', 'name', 'description', 'image', 'status'];

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
