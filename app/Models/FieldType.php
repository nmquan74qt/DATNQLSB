<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    protected $guarded = [];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
