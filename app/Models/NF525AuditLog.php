<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NF525AuditLog extends Model
{
    protected $fillable = [
        'event_type',
        'document_number',
        'user_id',
        'before_state',
        'after_state',
        'hash',
        'previous_hash',
        'event_timestamp'
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
        'event_timestamp' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
} 