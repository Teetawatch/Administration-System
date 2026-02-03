<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'priority' => 'integer',
    ];

    /**
     * Get the user who created this activity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get upcoming activities.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
                     ->whereIn('status', ['pending', 'ongoing'])
                     ->orderBy('start_date')
                     ->orderBy('priority');
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by priority.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', '<=', 2);
    }

    /**
     * Get the participants (personnel) for this activity.
     */
    public function participants()
    {
        return $this->belongsToMany(Personnel::class, 'activity_personnel');
    }
}
