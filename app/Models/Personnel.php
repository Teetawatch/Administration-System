<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';

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
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    /**
     * Get the user who created this personnel record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the full name with rank.
     */
    public function getFullNameAttribute(): string
    {
        $rank = $this->rank ? $this->rank . ' ' : '';
        return $rank . $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope to get only active personnel.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by department.
     */
    public function scopeDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to search by name.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('employee_id', 'like', "%{$search}%");
        });
    }

    /**
     * Get the activities this personnel has participated in.
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_personnel');
    }
}
