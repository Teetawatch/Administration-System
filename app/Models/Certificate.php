<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'issue_date',
        'personnel_name',
        'position',
        'purpose',
        'content',
        'status',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Get the user who created this certificate.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only issued certificates.
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope to get only draft certificates.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
