<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vehicle Model
 * 
 * Represents a vehicle in the system.
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $brand
 * @property string $license_plate
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Collection<VehicleBooking> $bookings
 * @property-read string $status_label
 * @property-read string $display_name
 */
class Vehicle extends Model
{
    use HasFactory;

    /**
     * Status constants for type safety.
     */
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_IN_USE = 'in_use';

    /**
     * Status labels in Thai.
     */
    public const STATUS_LABELS = [
        self::STATUS_AVAILABLE => 'พร้อมใช้งาน',
        self::STATUS_MAINTENANCE => 'ซ่อมบำรุง',
        self::STATUS_IN_USE => 'กำลังใช้งาน',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'brand',
        'license_plate',
        'status',
    ];

    /**
     * Get the bookings for this vehicle.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(VehicleBooking::class);
    }

    /**
     * Get status label in Thai.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get display name with license plate.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->license_plate})";
    }

    /**
     * Check if vehicle is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if vehicle is in maintenance.
     */
    public function isInMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    /**
     * Check if vehicle is in use.
     */
    public function isInUse(): bool
    {
        return $this->status === self::STATUS_IN_USE;
    }

    /**
     * Scope to get only available vehicles.
     *
     * @param Builder<Vehicle> $query
     * @return Builder<Vehicle>
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope to get vehicles not in maintenance.
     *
     * @param Builder<Vehicle> $query
     * @return Builder<Vehicle>
     */
    public function scopeBookable(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope to filter by status.
     *
     * @param Builder<Vehicle> $query
     * @param string $status
     * @return Builder<Vehicle>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
