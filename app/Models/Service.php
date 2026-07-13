<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'price', 'stock', 'description'];

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
