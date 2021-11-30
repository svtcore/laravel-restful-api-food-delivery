<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];

    protected $fillable = [
        'name',
        'working_time_start',
        'working_time_end',
        'working_day_start',
        'working_day_end',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function delivery_types()
    {
        return $this->hasMany(DeliveryType::class);
    }

    public function restaurant_addresses()
    {
        return $this->hasMany(RestaurantAddress::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users_restaurants');
    }
}
