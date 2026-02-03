<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Activity Model
 * 
 * Represents an activity/event in the system.
 * 
 * @property int $id
 * @property string $activity_name
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $location
 * @property string|null $description
 * @property string $status
 * @property int $priority
 * @property int|null $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User|null $creator
 * @property-read Collection<Personnel> $participants
 * @property-read string $status_label
 * @property-read string $priority_label
 * 
 * Following php-pro best practices:
 * - Full type declarations and return types
 * - Proper PHPDoc annotations for IDE support
 * - Constants for status and priority levels
 */
class Activity extends Model
{
    use HasFactory;

    /**
     * Status constants for type safety.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Status labels in Thai.
     */
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'รอดำเนินการ',
        self::STATUS_ONGOING => 'กำลังดำเนินการ',
        self::STATUS_COMPLETED => 'เสร็จสิ้น',
        self::STATUS_CANCELLED => 'ยกเลิก',
    ];

    /**
     * Priority labels.
     */
    public const PRIORITY_LABELS = [
        1 => 'สำคัญมากที่สุด',
        2 => 'สำคัญมาก',
        3 => 'สำคัญ',
        4 => 'ปกติ',
        5 => 'ต่ำ',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'activity_name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'description',
        'status',
        'priority',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'priority' => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this activity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the participants (personnel) for this activity.
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Personnel::class, 'activity_personnel');
    }

    /**
     * Get status label in Thai.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get priority label in Thai.
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITY_LABELS[$this->priority] ?? 'ปกติ';
    }

    /**
     * Check if activity is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if activity is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    /**
     * Check if activity is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if activity is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if activity is high priority (1 or 2).
     */
    public function isHighPriority(): bool
    {
        return $this->priority <= 2;
    }

    /**
     * Get participant count.
     */
    public function getParticipantCountAttribute(): int
    {
        return $this->participants->count();
    }

    /**
     * Scope to get upcoming activities.
     *
     * @param Builder<Activity> $query
     * @return Builder<Activity>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now()->toDateString())
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_ONGOING])
            ->orderBy('start_date')
            ->orderBy('priority');
    }

    /**
     * Scope to filter by status.
     *
     * @param Builder<Activity> $query
     * @param string $status
     * @return Builder<Activity>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get high priority activities.
     *
     * @param Builder<Activity> $query
     * @return Builder<Activity>
     */
    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->where('priority', '<=', 2);
    }

    /**
     * Scope to filter by date range.
     *
     * @param Builder<Activity> $query
     * @param string $startDate
     * @param string $endDate
     * @return Builder<Activity>
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    /**
     * Scope to search by name or location.
     *
     * @param Builder<Activity> $query
     * @param string $search
     * @return Builder<Activity>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('activity_name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get active (not cancelled) activities.
     *
     * @param Builder<Activity> $query
     * @return Builder<Activity>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }
}
