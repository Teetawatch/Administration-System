<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'name',
        'type',
        'brand',
        'license_plate',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(VehicleBooking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
