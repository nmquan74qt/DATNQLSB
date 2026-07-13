<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price_per_hour'];

    public function footballFields()
    {
        return $this->hasMany(FootballField::class);
    }
}
