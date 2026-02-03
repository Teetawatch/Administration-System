<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingDocument extends Model
{
    use HasFactory;

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

    protected $casts = [
        'document_date' => 'date',
        'is_secret' => 'boolean',
    ];

    /**
     * Get the user who created this document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
