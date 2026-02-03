<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NavyNews extends Model
{
    use HasFactory;

    protected $table = 'navy_news';

    protected $fillable = [
        'news_number',
        'news_date',
        'title',
        'urgency',
        'content',
        'attachment_path',
        'created_by',
    ];

    protected $casts = [
        'news_date' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * Get the user who created this news.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only published news.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
