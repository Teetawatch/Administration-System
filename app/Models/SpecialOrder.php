<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SpecialOrder Model
 * 
 * Represents a special order document in the system.
 * 
 * @property int $id
 * @property string $order_number
 * @property \Carbon\Carbon $order_date
 * @property string $subject
 * @property string|null $content
 * @property \Carbon\Carbon|null $effective_date
 * @property string $status
 * @property string|null $attachment_path
 * @property int|null $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User|null $creator
 * @property-read string $status_label
 * @property-read string|null $attachment_url
 */
class SpecialOrder extends Model
{
    use HasFactory;

    /**
     * Status constants for type safety.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Status labels in Thai.
     */
    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'ร่าง',
        self::STATUS_ACTIVE => 'มีผลบังคับใช้',
        self::STATUS_CANCELLED => 'ยกเลิก',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'order_date',
        'subject',
        'content',
        'effective_date',
        'status',
        'attachment_path',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'date',
        'effective_date' => 'date',
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this order.
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
     * Check if order is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if order has attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Scope to get only active orders.
     *
     * @param Builder<SpecialOrder> $query
     * @return Builder<SpecialOrder>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get only draft orders.
     *
     * @param Builder<SpecialOrder> $query
     * @return Builder<SpecialOrder>
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope to search by order number or subject.
     *
     * @param Builder<SpecialOrder> $query
     * @param string $search
     * @return Builder<SpecialOrder>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('order_number', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by status.
     *
     * @param Builder<SpecialOrder> $query
     * @param string $status
     * @return Builder<SpecialOrder>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
