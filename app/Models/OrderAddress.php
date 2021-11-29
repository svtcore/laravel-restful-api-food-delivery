<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'order_id',
        'city_id',
        'street_type_id',
        'id',
    ];

    protected $fillable = [
        'order_id',
        'city_id',
        'street_type_id',
        'street_name',
        'building_number',
        'entrace',
        'access_code',
        'floor',
        'apartment',
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function order_city()
    {
        return $this->belongsTo(OrderCity::class, 'city_id');
    }

    public function order_street_type()
    {
        return $this->belongsTo(OrderStreetType::class, 'street_type_id');
    }
}
