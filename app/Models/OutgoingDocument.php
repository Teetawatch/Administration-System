<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OutgoingDocument Model
 * 
 * Represents an outgoing document/letter in the system.
 * 
 * @property int $id
 * @property string $document_number
 * @property \Carbon\Carbon $document_date
 * @property string $to_recipient
 * @property string $subject
 * @property string $urgency
 * @property string|null $department
 * @property string|null $description
 * @property string|null $attachment_path
 * @property int|null $created_by
 * @property bool $is_secret
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User|null $creator
 * @property-read string $urgency_label
 * @property-read string|null $attachment_url
 * 
 * Following php-pro best practices:
 * - Full type declarations and return types
 * - Proper PHPDoc annotations for IDE support
 * - Constants for urgency levels
 */
class OutgoingDocument extends Model
{
    use HasFactory;

    /**
     * Urgency level constants for type safety.
     */
    public const URGENCY_NORMAL = 'normal';
    public const URGENCY_URGENT = 'urgent';
    public const URGENCY_VERY_URGENT = 'very_urgent';
    public const URGENCY_MOST_URGENT = 'most_urgent';

    /**
     * Urgency level labels in Thai.
     */
    public const URGENCY_LABELS = [
        self::URGENCY_NORMAL => 'ปกติ',
        self::URGENCY_URGENT => 'ด่วน',
        self::URGENCY_VERY_URGENT => 'ด่วนมาก',
        self::URGENCY_MOST_URGENT => 'ด่วนที่สุด',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_number',
        'document_date',
        'to_recipient',
        'subject',
        'urgency',
        'department',
        'description',
        'attachment_path',
        'created_by',
        'is_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'document_date' => 'date',
        'is_secret' => 'boolean',
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get urgency label in Thai.
     */
    public function getUrgencyLabelAttribute(): string
    {
        return self::URGENCY_LABELS[$this->urgency] ?? $this->urgency;
    }

    /**
     * Get attachment URL for display.
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }

        return asset('storage/' . $this->attachment_path);
    }

    /**
     * Check if document has attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Check if document is secret.
     */
    public function isSecret(): bool
    {
        return $this->is_secret === true;
    }

    /**
     * Check if document is urgent (any level).
     */
    public function isUrgent(): bool
    {
        return $this->urgency !== self::URGENCY_NORMAL;
    }

    /**
     * Scope to get only secret documents.
     *
     * @param Builder<OutgoingDocument> $query
     * @return Builder<OutgoingDocument>
     */
    public function scopeSecret(Builder $query): Builder
    {
        return $query->where('is_secret', true);
    }

    /**
     * Scope to get only non-secret documents.
     *
     * @param Builder<OutgoingDocument> $query
     * @return Builder<OutgoingDocument>
     */
    public function scopeNormal(Builder $query): Builder
    {
        return $query->where('is_secret', false);
    }

    /**
     * Scope to filter by urgency level.
     *
     * @param Builder<OutgoingDocument> $query
     * @param string $urgency
     * @return Builder<OutgoingDocument>
     */
    public function scopeUrgency(Builder $query, string $urgency): Builder
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Scope to filter by department.
     *
     * @param Builder<OutgoingDocument> $query
     * @param string $department
     * @return Builder<OutgoingDocument>
     */
    public function scopeDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to search by document number, subject, or recipient.
     *
     * @param Builder<OutgoingDocument> $query
     * @param string $search
     * @return Builder<OutgoingDocument>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('document_number', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")
                ->orWhere('to_recipient', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter documents within a date range.
     *
     * @param Builder<OutgoingDocument> $query
     * @param string $startDate
     * @param string $endDate
     * @return Builder<OutgoingDocument>
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('document_date', [$startDate, $endDate]);
    }
}
