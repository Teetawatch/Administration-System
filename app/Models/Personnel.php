<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Personnel Model
 * 
 * Represents a personnel/employee in the system.
 * 
 * @property int $id
 * @property string $employee_id
 * @property string|null $rank
 * @property string $first_name
 * @property string $last_name
 * @property string|null $position
 * @property string|null $department
 * @property string|null $phone
 * @property string|null $email
 * @property \Carbon\Carbon|null $hire_date
 * @property string $status
 * @property string|null $photo_path
 * @property int|null $created_by
 * @property int $sort_order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read string $full_name
 * @property-read User|null $creator
 * @property-read Collection<Activity> $activities
 * 
 * Following php-pro best practices:
 * - Full type declarations and return types
 * - Proper PHPDoc annotations for IDE support
 * - Typed properties and relationships
 */
class Personnel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'personnel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'rank',
        'first_name',
        'last_name',
        'position',
        'department',
        'phone',
        'email',
        'hire_date',
        'status',
        'photo_path',
        'created_by',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hire_date' => 'date',
        'sort_order' => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Status constants for type safety.
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_RETIRED = 'retired';

    /**
     * Get the user who created this personnel record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the activities this personnel has participated in.
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_personnel');
    }

    /**
     * Get the full name with rank.
     * 
     * Accessor attribute that combines rank, first name and last name.
     */
    public function getFullNameAttribute(): string
    {
        $rank = $this->rank ? "{$this->rank} " : '';
        return $rank . $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get photo URL for display.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        return asset('storage/' . $this->photo_path);
    }

    /**
     * Check if personnel is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Scope to get only active personnel.
     *
     * @param Builder<Personnel> $query
     * @return Builder<Personnel>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get only inactive personnel.
     *
     * @param Builder<Personnel> $query
     * @return Builder<Personnel>
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope to get only retired personnel.
     *
     * @param Builder<Personnel> $query
     * @return Builder<Personnel>
     */
    public function scopeRetired(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_RETIRED);
    }

    /**
     * Scope to filter by department.
     *
     * @param Builder<Personnel> $query
     * @param string $department
     * @return Builder<Personnel>
     */
    public function scopeDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to search by name, employee_id, or position.
     *
     * @param Builder<Personnel> $query
     * @param string $search
     * @return Builder<Personnel>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to order by sort_order then created_at.
     *
     * @param Builder<Personnel> $query
     * @return Builder<Personnel>
     */
    public function scopeDefaultOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');
    }
}
