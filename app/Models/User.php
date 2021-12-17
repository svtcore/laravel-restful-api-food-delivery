<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Http\Traits\HasRoleTrait;
use App\Http\Traits\IsOwnerTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoleTrait, IsOwnerTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_country_code',
        'phone_number',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function findForPassport($username) {
        $data = explode("_", $username);
        return $this->where('phone_number', intval($data[1]))->where('phone_country_code', intval($data[0]))->first();
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
