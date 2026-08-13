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

    public function images()
    {
        return $this->hasMany(FieldImage::class);
    }

    public function parent()
    {
        return $this->belongsTo(Field::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Field::class, 'parent_id');
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return 'https://images.pexels.com/photos/114296/pexels-photo-114296.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1';
        }
        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        
        $imagePath = $this->image;
        if (\Illuminate\Support\Str::startsWith($this->image, '[')) {
            $images = json_decode($this->image, true);
            if (is_array($images) && count($images) > 0) {
                $imagePath = $images[0];
            }
        }
        
        return asset('storage/' . $imagePath);
    }
}
