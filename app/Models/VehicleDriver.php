<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * VehicleDriver Model
 * 
 * Represents a vehicle driver in the system.
 * 
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Collection<VehicleBooking> $bookings
 * @property-read string $status_label
 */
class VehicleDriver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the bookings for this driver.
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
        return $this->is_active ? 'พร้อมปฏิบัติงาน' : 'ไม่พร้อมปฏิบัติงาน';
    }

    /**
     * Check if driver is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Scope to get only active drivers.
     *
     * @param Builder<VehicleDriver> $query
     * @return Builder<VehicleDriver>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive drivers.
     *
     * @param Builder<VehicleDriver> $query
     * @return Builder<VehicleDriver>
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to search by name.
     *
     * @param Builder<VehicleDriver> $query
     * @param string $search
     * @return Builder<VehicleDriver>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
