<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Certificate Model
 * 
 * Represents a certificate document in the system.
 * 
 * @property int $id
 * @property string $certificate_number
 * @property \Carbon\Carbon $issue_date
 * @property string|null $personnel_name
 * @property string|null $position
 * @property string|null $purpose
 * @property string|null $content
 * @property string $status
 * @property int|null $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User|null $creator
 * @property-read string $status_label
 */
class Certificate extends Model
{
    use HasFactory;

    /**
     * Status constants for type safety.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ISSUED = 'issued';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Status labels in Thai.
     */
    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'ร่าง',
        self::STATUS_ISSUED => 'ออกแล้ว',
        self::STATUS_CANCELLED => 'ยกเลิก',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this certificate.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status label in Thai.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Check if certificate is issued.
     */
    public function isIssued(): bool
    {
        return $this->status === self::STATUS_ISSUED;
    }

    /**
     * Check if certificate is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Scope to get only issued certificates.
     *
     * @param Builder<Certificate> $query
     * @return Builder<Certificate>
     */
    public function scopeIssued(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ISSUED);
    }

    /**
     * Scope to get only draft certificates.
     *
     * @param Builder<Certificate> $query
     * @return Builder<Certificate>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope to search by certificate number, name, or purpose.
     *
     * @param Builder<Certificate> $query
     * @param string $search
     * @return Builder<Certificate>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('certificate_number', 'like', "%{$search}%")
                ->orWhere('personnel_name', 'like', "%{$search}%")
                ->orWhere('purpose', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by status.
     *
     * @param Builder<Certificate> $query
     * @param string $status
     * @return Builder<Certificate>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
