<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCity extends Model
{
    use HasFactory;

    public function order_addresses() {
        return $this->hasMany(OrderAddress::class);
    }
}
