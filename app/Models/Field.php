<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $guarded = [];

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function parent()
    {
        return $this->belongsTo(Field::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Field::class, 'parent_id');
    }
}
