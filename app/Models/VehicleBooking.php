<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * VehicleBooking Model
 * 
 * Represents a vehicle booking in the system.
 * 
 * @property int $id
 * @property int $vehicle_id
 * @property int $user_id
 * @property int|null $vehicle_driver_id
 * @property \Carbon\Carbon $start_time
 * @property \Carbon\Carbon $end_time
 * @property string $destination
 * @property string $purpose
 * @property int|null $start_mileage
 * @property int|null $end_mileage
 * @property float|null $fuel_cost
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Vehicle $vehicle
 * @property-read User $user
 * @property-read VehicleDriver|null $driver
 * @property-read string $status_label
 * @property-read int|null $total_mileage
 */
class VehicleBooking extends Model
{
    use HasFactory;

    /**
     * Status constants for type safety.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Status labels in Thai.
     */
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'รอการอนุมัติ',
        self::STATUS_APPROVED => 'อนุมัติแล้ว',
        self::STATUS_COMPLETED => 'เสร็จสิ้น',
        self::STATUS_CANCELLED => 'ยกเลิก',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'start_mileage' => 'integer',
        'end_mileage' => 'integer',
        'fuel_cost' => 'decimal:2',
        'vehicle_id' => 'integer',
        'user_id' => 'integer',
        'vehicle_driver_id' => 'integer',
    ];

    /**
     * Get the vehicle for this booking.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver for this booking.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(VehicleDriver::class, 'vehicle_driver_id');
    }

    /**
     * Get the user who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status label in Thai.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get total mileage for the trip.
     */
    public function getTotalMileageAttribute(): ?int
    {
        if ($this->start_mileage === null || $this->end_mileage === null) {
            return null;
        }

        return $this->end_mileage - $this->start_mileage;
    }

    /**
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if booking is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if booking is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Scope to get only pending bookings.
     *
     * @param Builder<VehicleBooking> $query
     * @return Builder<VehicleBooking>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get only approved bookings.
     *
     * @param Builder<VehicleBooking> $query
     * @return Builder<VehicleBooking>
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope to get active bookings (not cancelled).
     *
     * @param Builder<VehicleBooking> $query
     * @return Builder<VehicleBooking>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Scope to filter by status.
     *
     * @param Builder<VehicleBooking> $query
     * @param string $status
     * @return Builder<VehicleBooking>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to check for overlapping bookings.
     *
     * @param Builder<VehicleBooking> $query
     * @param int $vehicleId
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeId
     * @return Builder<VehicleBooking>
     */
    public function scopeOverlapping(
        Builder $query,
        int $vehicleId,
        string $startTime,
        string $endTime,
        ?int $excludeId = null
    ): Builder {
        $query->where('vehicle_id', $vehicleId)
            ->where('status', '!=', self::STATUS_CANCELLED)
            ->where(function (Builder $q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function (Builder $inner) use ($startTime, $endTime) {
                        $inner->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }
}
