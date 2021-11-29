<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryType extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'restaurant_id',
    ];

    public function restaurants() {
        return $this->belongsTo(Restaurant::class);
    }
}
