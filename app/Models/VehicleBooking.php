<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBooking extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'vehicle_driver_id',
        'start_time',
        'end_time',
        'destination',
        'purpose',
        'start_mileage',
        'end_mileage',
        'fuel_cost',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function driver()
    {
        return $this->belongsTo(VehicleDriver::class, 'vehicle_driver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
