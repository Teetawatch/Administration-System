<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * NavyNews Model
 * 
 * Represents a navy news announcement in the system.
 * 
 * @property int $id
 * @property string $news_number
 * @property \Carbon\Carbon $news_date
 * @property string $title
 * @property string $urgency
 * @property string|null $content
 * @property string|null $attachment_path
 * @property int|null $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read User|null $creator
 * @property-read string $urgency_label
 * @property-read string|null $attachment_url
 */
class NavyNews extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'navy_news';

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
        'news_number',
        'news_date',
        'title',
        'urgency',
        'content',
        'attachment_path',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'news_date' => 'date',
        'created_by' => 'integer',
    ];

    /**
     * Get the user who created this news.
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
     * Check if news has attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Check if news is urgent (any level).
     */
    public function isUrgent(): bool
    {
        return $this->urgency !== self::URGENCY_NORMAL;
    }

    /**
     * Scope to filter by urgency level.
     *
     * @param Builder<NavyNews> $query
     * @param string $urgency
     * @return Builder<NavyNews>
     */
    public function scopeUrgency(Builder $query, string $urgency): Builder
    {
        return $query->where('urgency', $urgency);
    }

    /**
     * Scope to search by news number or title.
     *
     * @param Builder<NavyNews> $query
     * @param string $search
     * @return Builder<NavyNews>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('news_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to get only urgent news.
     *
     * @param Builder<NavyNews> $query
     * @return Builder<NavyNews>
     */
    public function scopeOnlyUrgent(Builder $query): Builder
    {
        return $query->where('urgency', '!=', self::URGENCY_NORMAL);
    }
}
