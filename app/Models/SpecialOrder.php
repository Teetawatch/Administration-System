<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialOrder extends Model
{
    use HasFactory;

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

    protected $casts = [
        'order_date' => 'date',
        'effective_date' => 'date',
    ];

    /**
     * Get the user who created this order.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only active orders.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


}
